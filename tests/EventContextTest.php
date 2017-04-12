<?php

namespace Test;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Exception\RuntimeException as FormRuntimeException;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryBuilder;

class EventContextTest extends \PHPUnit_Framework_TestCase
{
    protected $contextEvents;

    public function setUp()
    {
        $this->contextEvents = [];
    }

    private function registerTraceableListener(FormBuilderInterface$builder)
    {
        $eventListenerCallable = function(FormEvent $event, $eventName) {
            $this->contextEvents[] = [];
            $item = &$this->contextEvents[count($this->contextEvents) - 1];
            $item['event'] = $eventName;

            try {
                $item['model_data'] = $event->getForm()->getData();
            } catch (FormRuntimeException $exception) {
                $item['model_data'] = null;
            }
            try {
                $item['normalized_data'] = $event->getForm()->getNormData();
            } catch (FormRuntimeException $exception) {
                $item['normalized_data'] = null;
            }
            try {
                $item['view_data'] = $event->getForm()->getViewData();
            } catch (FormRuntimeException $exception) {
                $item['view_data'] = null;
            }
        };
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, $eventListenerCallable)
            ->addEventListener(FormEvents::POST_SET_DATA, $eventListenerCallable)
            ->addEventListener(FormEvents::PRE_SUBMIT, $eventListenerCallable)
            ->addEventListener(FormEvents::SUBMIT, $eventListenerCallable)
            ->addEventListener(FormEvents::POST_SUBMIT, $eventListenerCallable)
        ;
    }

    /**
     * @param $type
     * @param $data
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createForm($type, $data)
    {
        $formBuilder = (new FormFactoryBuilder())
            ->getFormFactory()
            ->createBuilder($type, $data);

        $formBuilder
            ->add('title', TextType::class);

        $this->registerTraceableListener($formBuilder);
        return $formBuilder->getForm();
    }

    public function testEventWhenCreateForm()
    {
        $exceptedDataEvents = [
            [
                'event' => FormEvents::PRE_SET_DATA,
                'model_data' => null,
                'normalized_data' => null,
                'view_data' => null,
            ],
            [
                'event' => FormEvents::POST_SET_DATA,
                'model_data' => [],
                'normalized_data' => [],
                'view_data' => [],
            ],
        ];

        $this->createForm(FormType::class, []);

        $this->assertEquals($exceptedDataEvents, $this->contextEvents);
    }

    public function testEventWhenSubmitForm()
    {
        $exceptedDataEvents = [
            [
                'event' => FormEvents::PRE_SET_DATA,
                'model_data' => null,
                'normalized_data' => null,
                'view_data' => null,
            ],
            [
                'event' => FormEvents::POST_SET_DATA,
                'model_data' => [
                    'title' => 'red',
                ],
                'normalized_data' => [
                    'title' => 'red',
                ],
                'view_data' => [
                    'title' => 'red',
                ],
            ],
            [
                'event' => FormEvents::PRE_SUBMIT,
                'model_data' => [
                    'title' => 'red',
                ],
                'normalized_data' => [
                    'title' => 'red',
                ],
                'view_data' => [
                    'title' => 'red',
                ],
            ],
            [
                'event' => FormEvents::SUBMIT,
                'model_data' => [
                    'title' => 'red',
                ],
                'normalized_data' => [
                    'title' => 'red',
                ],
                'view_data' => [
                    'title' => 'red',
                ],
            ],
            [
                'event' => FormEvents::POST_SUBMIT,
                'model_data' => [
                    'title' => 'green',
                ],
                'normalized_data' => [
                    'title' => 'green',
                ],
                'view_data' => [
                    'title' => 'green',
                ],
            ],
        ];

        $this->createForm(FormType::class, ['title' => 'red'])->submit(['title' => 'green']);

        $this->assertEquals($exceptedDataEvents, $this->contextEvents);
    }

    public function testEventWhenSetData()
    {
        $exceptedDataEvents = [
            [
                'event' => FormEvents::PRE_SET_DATA,
                'model_data' => null,
                'normalized_data' => null,
                'view_data' => null,
            ],
            [
                'event' => FormEvents::POST_SET_DATA,
                'model_data' => [
                    'title' => 'red',
                ],
                'normalized_data' => [
                    'title' => 'red',
                ],
                'view_data' => [
                    'title' => 'red',
                ],
            ],
        ];

        $this->createForm(FormType::class, ['title' => 'red'])->setData(['title' => 'green']);
        $this->assertEquals($exceptedDataEvents, $this->contextEvents);
    }
}