<?php
/**
 * Created by PhpStorm.
 * User: andreykopkin
 * Date: 30.11.17
 * Time: 16:42
 */

namespace CraftKeen\BrochureBuilderBundle\Service;

use CraftKeen\BrochureBuilderBundle\FileManager\FileManagerBridge;
use CraftKeen\BrochureBuilderBundle\FileManager\FileSystemDriver;
use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\FCRBundle\Entity\Property;
use CraftKeen\FCRBundle\Entity\PropertyGallery;
use CraftKeen\FCRBundle\Entity\Tenant;
use Doctrine\Bundle\DoctrineBundle\Registry;
use FM\ElFinderPHP\Connector\ElFinderConnector;
use Spatie\PdfToImage\Exceptions\PdfDoesNotExist;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileManagerService
{

    const ELFINDER_INSTANCE = 'brochure';

    /**
     * @var ContainerInterface
     */
    private $container = null;

    /**
     * @var Registry
     */
    private $doctrine = null;

    /**
     * FileManagerService constructor.
     * @param ContainerInterface $container
     * @param Registry $doctrine
     */
    public function __construct(ContainerInterface $container, Registry $doctrine)
    {
        $this->container = $container;
        $this->doctrine = $doctrine;
    }

    /**
     * @param $path
     * @param $property
     */
    private function prepareDirectory($path, Property $property, &$sitePlans = []) {
        $propertyId = $property->getId();
        $prefix = dirname($path) . DIRECTORY_SEPARATOR . $propertyId . DIRECTORY_SEPARATOR;
        if (!@is_dir(prefix . FileSystemDriver::PROPERTY_IMAGES_DIRECTORY)) {
            @mkdir($prefix . FileSystemDriver::PROPERTY_IMAGES_DIRECTORY, 0777, true);
        }

        if (!@is_dir(prefix . FileSystemDriver::PROPERTY_TENANTS_DIRECTORY)) {
            @mkdir($prefix . FileSystemDriver::PROPERTY_TENANTS_DIRECTORY, 0777, true);
        }

        if (!@is_dir(prefix . FileSystemDriver::SITE_PLANS_DIRECTORY)) {
            @mkdir($prefix . FileSystemDriver::SITE_PLANS_DIRECTORY, 0777, true);
        }

        $this->preparePropertyImagesDirectory($prefix . FileSystemDriver::PROPERTY_IMAGES_DIRECTORY, $property);
        $this->preparePropertyTenantsDirectory($prefix . FileSystemDriver::PROPERTY_TENANTS_DIRECTORY, $property);
        $this->prepareSitePlansDirectory($prefix . FileSystemDriver::SITE_PLANS_DIRECTORY, $property, $sitePlans);
    }

    /**
     * Prepare images directory
     *
     * @param $path
     * @param $property
     */
    private function preparePropertyImagesDirectory($path, Property $property) {
        $images = $this->doctrine->getRepository(PropertyGallery::class)->findByProperty($property->getId());
        $this->prepareImageDirectory($path, $images);
    }

    /**
     * Prepare tenant directory
     *
     * @param $path
     * @param $property
     */
    private function preparePropertyTenantsDirectory($path, Property $property) {
        $images = $property->getTenants();
        $this->prepareImageDirectory($path, $images);
    }

    /**
     * Prepare directory and put image symlink to it
     *
     * @param $path
     * @param $images
     */
    private function prepareImageDirectory($path, $images) {

        chmod($path, 0777);

        // $files = scandir($path);
        // foreach ($files as $file) {
        //     if (is_link($path . DIRECTORY_SEPARATOR . $file)) {
        //         unlink($path . DIRECTORY_SEPARATOR . $file);
        //     }
        // }

        foreach ($images as $image) {
            $name = $image->getId() . '_' . basename($image->getImage());
            $fullName = $path . DIRECTORY_SEPARATOR . $name;

            if (!file_exists($fullName)) {
                $file = file_get_contents($image->getImage());
                file_put_contents($fullName, $file);
                chmod($fullName, 0444);
            }
        }

        chmod($path, 0555);
    }

    /**
     * Crop and save a site plan image (png only)
     *
     * @param $fileName         source image
     * @param $destFileName     cropped image name
     * @param $top              top indent
     * @param $bottom           bottom indent
     * @param $quality          png image quality
     */
    private function cropSitePlanImage($fileName, $destFileName, $top = 420, $bottom = 305, $quality = 9) {
        $source_image = imagecreatefrompng($fileName);
        $image_info = getimagesize($fileName);
        list(
            $original_w,
            $original_h
        ) = $image_info;

        $dest_image = imagecreatetruecolor($original_w, $original_h);
        $background = imagecolorallocate($dest_image, 255, 255, 255);
        imagefill($dest_image, 0, 0, $background);

        imagecopyresampled(
            $dest_image,
            $source_image,
            0, // dst_x
            $top, // dst_y
            0, // src_x
            $top, // src_y
            $original_w, // dst_w
            $original_h - $top - $bottom, // dst_h
            $original_w, // src_w
            $original_h - $top - $bottom // src_h
        );

        imagepng($dest_image, $destFileName, $quality);

        imagedestroy($dest_image);
        imagedestroy($source_image);

        chmod($destFileName, 0444);
    }

    /**
     * Prepare directory and put images from  pdf files to it
     *
     * @param $path
     * @param $property
     */
    private function prepareSitePlansDirectory($path, $property, &$sitePlans = []) {
        chmod($path, 0777);
        touch($path . DIRECTORY_SEPARATOR . '.files');

        $sitePlans = [];
        $files = file($path . DIRECTORY_SEPARATOR . '.files', FILE_IGNORE_NEW_LINES);

        /** @var Property $property */
        if ($property && $property->getDetails()) {
            // Only parse changed files
            $pdfLink = $property->getDetails()->getSitePlanPdf();
            if (!$pdfLink) {
                return;
            }

            $pdfLinkUrl = $pdfLink;
            
            // Fix a link encoding
            $pdfLinkMeta = @parse_url($pdfLink);
            if (
                is_array($pdfLinkMeta) &&
                isset($pdfLinkMeta['scheme'], $pdfLinkMeta['host'], $pdfLinkMeta['path']) &&
                !empty($pdfLinkMeta['scheme']) && !empty($pdfLinkMeta['host']) && !empty($pdfLinkMeta['path']) &&
                strpos($pdfLinkMeta['path'], ' ')
            ) {
                $pdfLinkUrl = $pdfLinkMeta['scheme'].'://'.$pdfLinkMeta['host'].
                    implode('/', array_map('rawurlencode', explode('/', $pdfLinkMeta['path'])));
            }
                
            $pdfLinkArray = explode('/', $pdfLinkUrl);
            $pdfNameFromUrl = end($pdfLinkArray);
            $pdfName = @rawurldecode($pdfNameFromUrl);
            if (!$pdfName) {
                $pdfName = $pdfNameFromUrl;
            }

            if (!in_array($pdfLink, $files)) {
                try {
                    $spatie = new \Spatie\PdfToImage\Pdf($pdfLinkUrl);

                    $spatie->setCompressionQuality(100);
                    $spatie->setResolution(300);
                    $spatie->setOutputFormat('png');
                    $spatie->setColorspace(\Imagick::COLORSPACE_GRAY);
                    $spatie->setLayerMethod(null);

                    foreach (range(1, $spatie->getNumberOfPages()) as $pageNumber) {
                        $fileName = $path . DIRECTORY_SEPARATOR . $pdfName . '_page' . $pageNumber . '.png';
                        $spatie->setPage($pageNumber)->saveImage($fileName);
                        chmod($fileName, 0444);

                        $this->cropSitePlanImage($fileName, preg_replace('/\.png$/i', '_cropped.png', $fileName));
                    }
                    // save name to folder for preserve duplicate parsing
                    file_put_contents($path . DIRECTORY_SEPARATOR . '.files', $pdfLink . "\r\n", FILE_APPEND);
                } catch (\Exception $e) {}
            }

            $sitePlans = glob($path . DIRECTORY_SEPARATOR . $pdfName . '*_cropped.png');
        }

        chmod($path, 0555);
    }

    /**
     * @param $propertyId
     * @param User $user
     */
    public function getSitePlans($propertyId, User $user) {
        $sitePlans = [];
        $this->getConnector($propertyId, $user, $sitePlans);
        return $sitePlans;
    }

    /**
     * Retrieve connector for elfinder instance
     *
     * @param $propertyId
     * @param User $user
     * @return ElFinderConnector
     */
    public function getConnector($propertyId, User $user, &$sitePlans = []) {
        $configurator = $this->container->get('fm_elfinder.configurator');
        $parameters = $configurator->getConfiguration(self::ELFINDER_INSTANCE);

        $property = $propertyId && is_numeric($propertyId) ? 
            $this->doctrine->getRepository(Property::class)->find($propertyId) : null;

        foreach ($parameters['roots'] as $index => &$options) {

            if (!$property && $options['alias'] === 'Property') {
                $options = null;
                continue;
            }

            if(!is_dir($options['path'])) {
                mkdir($options['path'],0777,true);
            }

            if ($options['alias'] === 'Property') {
                $options['URL'] .= $property->getId();
                $options['path'] .= DIRECTORY_SEPARATOR . $property->getId();
                if(!is_dir($options['path'])) {
                    mkdir($options['path']);
                }

                $this->prepareDirectory($options['path'], $property, $sitePlans);
            }

            //Override custom filesystem driver
            $options['service'] = new FileSystemDriver(
                $this->doctrine,
                $user,
                $this->container,
                $options['alias'] === 'Property' ? $property : null
            );

            $options['service']->clearstatcache();
        }

        return new ElFinderConnector(new FileManagerBridge($parameters));
    }
}
