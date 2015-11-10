<?php
namespace ClassExample;
use Form\ChoiceList\CustomChoiceList;
use Form\EventListener\CustomEventListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ClassExample\OneData;

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
            ->add("un", "text")
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
            ->add("type_text", "text", array("mapped" => false, "label" => "mon label"))
            ->add("type_textarea", "textarea", array("mapped" => false))
            ->add("type_email", "email", array("mapped" => false))
            ->add("type_integer", "integer", array("mapped" => false))
            ->add("type_money", "money", array("mapped" => false))
            ->add("type_number", "number", array("mapped" => false))
            ->add("type_password", "password", array("mapped" => false))
            ->add("type_percent", "percent", array("mapped" => false))
            ->add("type_search", "search", array("mapped" => false))
            ->add("type_url", "url", array("mapped" => false))
            ;

        $builder
            ->add("type_choice__list", "choice", array(
                "mapped" => false,
                "placeholder" => "un choix",
                "choice_list" =>  new ChoiceList(
                    array(1, 0.5, 0.1),
                    array('Full', 'Half', 'Almost empty')
                ),
                "expanded" => false,
                "multiple" => true,
            ))
            ->add("type_choice__loader", "choice", array(
                "mapped" => false,
                "placeholder" => "un choix",
                "choice_list" =>  new ChoiceList(
                    array(1, 0.5, 0.1),
                    array('Full', 'Half', 'Almost empty')
                ),
                "disabled" => true
            ))
            ->add("type_country", "country", array("mapped" => false))
            ->add("type_language", "language", array("mapped" => false))
            ->add("type_locale", "locale", array("mapped" => false))
            ->add("type_timezone", "timezone", array("mapped" => false))
            ->add("currency", "currency", array("mapped" => false))
            ;

        $builder
            ->add("type_date", "date", array("mapped" => false))
            ->add("type_datetime", "datetime", array("mapped" => false))
            ->add("type_time", "time", array("mapped" => false))
            ->add("type_birthday", "birthday", array("mapped" => false))
        ;

        $builder
            ->add("type_checkbox", "checkbox", array("mapped" => false))
            //->add("type_file", "file", array("mapped" => false))
            ->add("type_radio", "radio", array("mapped" => false))
            ;

        $builder
            ->add("type_collection", "collection", array("mapped" => false))
            ->add("type_repeated", "repeated", array("mapped" => false))
            ;

        $builder
            ->add("type_hidden", "hidden", array("mapped" => false))
            ;

        $builder
            ->add("type_button", "button")
            ->add("type_reset", "reset")
            ->add("type_submit", "submit")
            ;

        $builder
            ->add("type_form", "form", array("mapped" => false));
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