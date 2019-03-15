<?php

namespace CraftKeen\BrochureBuilderBundle\Twig;


use CraftKeen\BrochureBuilderBundle\Entity\FileManagerObject;
use Doctrine\Bundle\DoctrineBundle\Registry;

class ImageDecodeExtension extends \Twig_Extension
{

    /**
     * @var Registry
     */
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('base64img', [$this, 'getBaseFromImage']),
            new \Twig_SimpleFunction('base64file', [$this, 'getBaseFromFile']),
        ];
    }

    /**
     * Build base64 encode string from path
     *
     * @param $path
     * @return string
     * @throws \Twig_Error_Runtime
     */
    public function getBaseFromImage($path)
    {
        if (!is_file($path)) {
            throw new \Twig_Error_Runtime("File not found: " . $path);
        }
        $mime = mime_content_type($path);

        return "data:{$mime};base64," . base64_encode(file_get_contents($path));
    }

    /**
     * Build base64 encode string from file
     *
     * @param $fileId
     * @return string
     * @throws \Twig_Error_Runtime
     */
    public function getBaseFromFile($fileId)
    {
        $file = $this->doctrine->getRepository(FileManagerObject::class)->find($fileId);
        if (!$file) {
            throw new \Twig_Error_Runtime("File not found: #" . $fileId);
        }
        return $this->getBaseFromImage($file->getPath());
    }
}
