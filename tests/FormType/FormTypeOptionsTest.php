<?php

namespace tests\FormType;

use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Traits\FormTypeTestTrait;

class FormTypeOptionsTest extends \PHPUnit_Framework_TestCase
{
    use FormTypeTestTrait;

    public function provideTestOptions()
    {
        yield [BirthdayType::class];

        yield [ButtonType::class];

        yield [CheckboxType::class];

        yield [ChoiceType::class];

        yield [CollectionType::class];

        yield [CountryType::class];

        yield [CurrencyType::class];

        yield [
            FormType::class,
            [
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
            ]
        ];

        yield [HiddenType::class, []];

        yield [RangeType::class, []];

        yield [MoneyType::class, ['currency', 'divisor', 'grouping', 'scale']];

        yield [FileType::class, ['multiple']];

        yield [PasswordType::class, ['always_empty', 'trim']];

        yield [RepeatedType::class, ['type', 'first_name', 'first_options', 'options', 'second_name', 'second_options']];

        yield [DateType::class, [
            'choice_translation_domain',
            'days',
            'format',
            'html5',
            'input',
            'model_timezone',
            'months',
            'placeholder',
            'view_timezone',
            'widget',
            'years',
        ]];

        yield [DateTimeType::class, [
            'choice_translation_domain',
            'date_format',
            'date_widget',
            'days',
            'format',
            'hours',
            'html5',
            'input',
            'minutes',
            'model_timezone',
            'months',
            'placeholder',
            'seconds',
            'time_widget',
            'view_timezone',
            'widget',
            'with_minutes',
            'with_seconds',
            'years'
        ]];

        yield [EmailType::class, []];
    }

    /**
     * @dataProvider provideTestOptions
     * @param array $expected
     * @param $class
     */
    public function testOptions($class, array $expected = [])
    {
        $this->assertFormHasOptionsAvailable($expected, $class);
    }

    public function testTypes()
    {
        $expected = array_column(iterator_to_array($this->provideTestOptions()), 0);
        $types = new Class extends CoreExtension
        {
            public function getTypes()
            {
                return $this->loadTypes();
            }
        };
        $types = array_map('get_class', $types->getTypes());

        $getShortName = function($item) { return substr(strrchr($item, "\\"), 1); };
        $expected = array_map($getShortName, $expected);
        $types = array_map($getShortName, $types);

        sort($expected);
        sort($types);

        $this->assertEquals($expected, $types);
    }
}