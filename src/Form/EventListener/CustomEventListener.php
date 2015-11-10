<?php
namespace Form\EventListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Created by PhpStorm.
 * User: prestataire
 * Date: 10/11/15
 * Time: 12:13
 */
class CustomEventListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'onPreSetData',
            FormEvents::PRE_SUBMIT   => 'onPreSubmit',
            FormEvents::POST_SET_DATA => 'onPostSetData',
            FormEvents::POST_SUBMIT => 'onPostSubmit',
            FormEvents::SUBMIT => 'onSubmit'
        );
    }

    public function onPreSetData(FormEvent $event)
    {
        $user = $event->getData();
        $form = $event->getForm();

        echo "onPreSetData".PHP_EOL;
    }

    public function onPostSetData(FormEvent $event)
    {
        echo "onPostSetData".PHP_EOL;
    }

    public function onPreSubmit(FormEvent $event)
    {
        $user = $event->getData();
        $form = $event->getForm();

        echo "onPreSubmit".PHP_EOL;
    }

    public function onSubmit(FormEvent $event)
    {
        echo "onSubmit".PHP_EOL;
    }

    public function onPostSubmit(FormEvent $event)
    {
        echo "onPostSubmit".PHP_EOL;
    }
}