<?php

namespace CraftKeen\Bundle\TranslationBundle\Tests\Unit\Form;

use CraftKeen\Bundle\TranslationBundle\Form\Type\LanguageType;
use Symfony\Component\Form\Test\FormIntegrationTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageTypeTest extends FormIntegrationTestCase
{

    /** @var LanguageType */
    protected $type;

    protected function setUp()
    {
        parent::setUp();

        $this->type = new LanguageType();
    }

    public function testConfigureOptions()
    {
        $this->type->setDataClass(\stdClass::class);
        /** @var OptionsResolver|\PHPUnit_Framework_MockObject_MockObject $resolver */
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver->expects($this->once())->method('setDefaults')->with(['data_class' => \stdClass::class]);
        $this->type->configureOptions($resolver);
    }

    public function testSubmit()
    {
        $form = $this->factory->create(LanguageType::class);
        $this->assertNull($form->getData());

        $form->submit(['code' => 'en']);

        $this->assertTrue($form->isValid());
        $this->assertEquals(['code' => 'en'], $form->getData());
    }
}
