<?php
namespace ClassExample;
use Form\ChoiceList\CustomChoiceList;
use Form\EventListener\CustomEventListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\ChoiceList\View\ChoiceListView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ClassExample\OneData;

use Symfony\Component\Form\Extension\Core\Type as FormType;

/**
 * Created by PhpStorm.
 * User: prestataire
 * Date: 10/11/15
 * Time: 10:08
 */
class OneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("un", FormType\TextType::class)
            ->add("typebizare")
            ;

        $this->testAllTypes($builder);

        $builder
            ->addEventSubscriber(new CustomEventListener())
        ;
    }

    protected function testAllTypes(FormBuilderInterface $builder)
    {
        $builder
            ->add("type_text",          FormType\TextType::class, array("mapped" => false, "label" => "mon label"))
            ->add("type_textarea",      FormType\TextareaType::class, array("mapped" => false))
            ->add("type_email",         FormType\EmailType::class, array("mapped" => false))
            ->add("type_integer",       FormType\IntegerType::class, array("mapped" => false))
            ->add("type_money",         FormType\MoneyType::class, array("mapped" => false))
            ->add("type_number",        FormType\NumberType::class, array("mapped" => false))
            ->add("type_password",      FormType\PasswordType::class, array("mapped" => false))
            ->add("type_percent",       FormType\PercentType::class, array("mapped" => false))
            ->add("type_search",        FormType\SearchType::class, array("mapped" => false))
            ->add("type_url",           FormType\UrlType::class, array("mapped" => false))
            ;

        $builder
            ->add("type_choice__list",  FormType\ChoiceType::class, array(
                "mapped" => false,
                "placeholder" => "un choix",
                "choices" =>  array(
                    array('Full' => 1, 'Half' => 0.5, 'Almost empty' => 0.1)
                ),
                "expanded" => false,
                "multiple" => true,
            ))
            ->add("type_choice__loader", FormType\ChoiceType::class, array(
                "mapped" => false,
                "placeholder" => "un choix",
                "choices" =>  array(
                    array('Full' => 1, 'Half' => 0.5, 'Almost empty' => 0.1)
                ),
                "disabled" => true
            ))
            ->add("type_country",       FormType\CountryType::class, array("mapped" => false))
            ->add("type_language",      FormType\LanguageType::class, array("mapped" => false))
            ->add("type_locale",        FormType\LocaleType::class, array("mapped" => false))
            ->add("type_timezone",      FormType\TimezoneType::class, array("mapped" => false))
            ->add("currency",           FormType\CurrencyType::class, array("mapped" => false))
            ;

        $builder
            ->add("type_date",          FormType\DateType::class, array("mapped" => false))
            ->add("type_datetime",      FormType\DateTimeType::class, array("mapped" => false))
            ->add("type_time",          FormType\TimeType::class, array("mapped" => false))
            ->add("type_birthday",      FormType\BirthdayType::class, array("mapped" => false))
        ;

        $builder
            ->add("type_checkbox",      FormType\CheckboxType::class, array("mapped" => false))
            //->add("type_file", "file", array("mapped" => false))
            ->add("type_radio",         FormType\RadioType::class, array("mapped" => false))
            ;

        $builder
            ->add("type_collection",    FormType\CollectionType::class, array("mapped" => false))
            ->add("type_repeated",      FormType\RepeatedType::class, array("mapped" => false))
            ;

        $builder
            ->add("type_hidden",        FormType\HiddenType::class, array("mapped" => false))
            ;

        $builder
            ->add("type_button",        FormType\ButtonType::class)
            ->add("type_reset",         FormType\ResetType::class)
            ->add("type_submit",        FormType\SubmitType::class)
            ;

        $builder
            ->add("type_form", FormType\FormType::class, array("mapped" => false));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault("data_class", OneData::class);
    }

    public function getName()
    {
        return "one";
    }
}