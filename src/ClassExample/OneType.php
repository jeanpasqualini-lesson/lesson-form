<?php
namespace ClassExample;
use Form\EventListener\CustomEventListener;
use Symfony\Component\Form\AbstractType;
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

        $builder
            ->addEventSubscriber(new CustomEventListener())
        ;
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