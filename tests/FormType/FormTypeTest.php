<?php

namespace tests\FormType;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testAvailableOption()
    {
        $optionResolver = new OptionsResolver();
        $type = new FormType();
        $type->configureOptions($optionResolver);

        $optionsAvailable = $optionResolver->getDefinedOptions();
        $expected = [
            'required',         'trim',
            'action',           'method',           'attr',
            'data_class',       'data',             'empty_data',
            'error_bubbling',   'mapped',
            'inherit_data',
            'label_attr',       'label_format',     'post_max_size_message',
            'property_path',
            'translation_domain',
            'label',
            'auto_initialize',
            'block_name',
            'by_reference',
            'compound',
            'disabled'
        ];

        sort($expected);
        sort($optionsAvailable);

        $this->assertEquals($expected, $optionsAvailable, 'diff between expected and real');
    }
}