<?php

namespace CraftKeen\Bundle\TranslationBundle\Tests\Unit\Twig;

use CraftKeen\Bundle\TranslationBundle\Provider\LanguageProvider;
use CraftKeen\Bundle\TranslationBundle\Twig\TranslationTwigExtension;

class TranslationTwigExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|LanguageProvider */
    protected $languageProvider;

    /** @var TranslationTwigExtension */
    protected $extension;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->languageProvider = $this->createMock(LanguageProvider::class);
        $this->extension = new TranslationTwigExtension($this->languageProvider);
    }

    public function testGetFilters()
    {
        $this->assertEquals(
            [],
            $this->extension->getFilters()
        );
    }

    public function testGetFunctions()
    {
        $this->assertEquals(
            [
                new \Twig_Function('language_current', [$this->languageProvider, 'getCurrentLanguage']),
                new \Twig_Function('available_languages', [$this->languageProvider, 'getLanguages']),
            ],
            $this->extension->getFunctions()
        );
    }
}
