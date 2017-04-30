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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Form;
use Traits\FormTypeTestTrait;

class FormTypeOptionsTest extends \PHPUnit_Framework_TestCase
{
    use FormTypeTestTrait;

    public function provideTestOptions()
    {
        yield [BirthdayType::class, [], DateType::class];

        yield [ButtonType::class, [], null];

        yield [CheckboxType::class, ['value'], FormType::class];

        yield [ChoiceType::class, [
            'choice_attr',
            'choice_label',
            'choice_loader',
            'choice_name',
            'choice_translation_domain',
            'choice_value',
            'choices',
            'choices_as_values',
            'expanded',
            'group_by',
            'multiple',
            'placeholder',
            'preferred_choices',
        ], FormType::class];

        yield [CollectionType::class, [
            'allow_add',
            'allow_delete',
            'delete_empty',
            'entry_options',
            'entry_type',
            'prototype',
            'prototype_data',
            'prototype_name',
        ], FormType::class];

        yield [CountryType::class, [], ChoiceType::class];

        yield [CurrencyType::class, [], ChoiceType::class];

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
        ], FormType::class];

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
        ], FormType::class];

        yield [EmailType::class, [], TextType::class];

        yield [FileType::class, ['multiple'], FormType::class];

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
            ],
            null
        ];

        yield [HiddenType::class, [], FormType::class];

        yield [IntegerType::class, ['grouping', 'rounding_mode', 'scale'], FormType::class];

        yield [LanguageType::class, [], ChoiceType::class];

        yield [LocaleType::class, [], ChoiceType::class];

        yield [MoneyType::class, ['currency', 'divisor', 'grouping', 'scale'], FormType::class];

        yield [NumberType::class, ['grouping', 'rounding_mode', 'scale'], FormType::class];

        yield [PasswordType::class, ['always_empty', 'trim'], TextType::class];

        yield [PercentType::class, ['scale', 'type'], FormType::class];

        yield [RadioType::class, [], CheckboxType::class];

        yield [RangeType::class, [], TextType::class];

        yield [RepeatedType::class, ['type', 'first_name', 'first_options', 'options', 'second_name', 'second_options'], FormType::class];

        yield [ResetType::class, [], ButtonType::class];

        yield [SearchType::class, [], TextType::class];

        yield [SubmitType::class, [], ButtonType::class];

        yield [TextType::class, [], FormType::class];

        yield [TextareaType::class, [], TextType::class];

        yield [TimeType::class, [
            'choice_translation_domain',
            'hours',
            'html5',
            'input',
            'minutes',
            'model_timezone',
            'placeholder',
            'seconds',
            'view_timezone',
            'widget',
            'with_minutes',
            'with_seconds'
        ], FormType::class];

        yield [TimezoneType::class];

        yield [UrlType::class, ['default_protocol']];
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

    private function getTypes($string = true)
    {
        $types = new Class extends CoreExtension
        {
            public function getTypes()
            {
                return $this->loadTypes();
            }
        };

        if (!$string) {
           return $types->getTypes();
        }

        return array_map('get_class', $types->getTypes());
    }

    public function testTypes()
    {
        $expected = array_column(iterator_to_array($this->provideTestOptions()), 0);
        $types = $this->getTypes();
        $getShortName = function($item) { return substr(strrchr($item, "\\"), 1); };
        $expected = array_map($getShortName, $expected);
        $types = array_map($getShortName, $types);

        sort($expected);
        sort($types);

        $this->assertEquals($expected, $types);
    }

    /**
     * @dataProvider provideTestOptions
     */
    public function testParent($class, $expectOptions = null, $expectParentType = false)
    {
        if (false !== $expectParentType) {
            $this->assertEquals($expectParentType, (new $class())->getParent(), $class);
        }
    }
}