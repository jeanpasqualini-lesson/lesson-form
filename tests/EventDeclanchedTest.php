<?php
namespace Test;

use Symfony\Component\Form\Exception\RuntimeException as FormRuntimeException;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryBuilder;

class EventDeclanchedTest extends \PHPUnit_Framework_TestCase
{
    protected $calledEvents;
    protected $contextEvents;

    public function setUp()
    {
        $this->calledEvents = [];
        $this->contextEvents = [];
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
        $this->registerTraceableListener($formBuilder);
        return $formBuilder->getForm();
    }

    private function registerTraceableListener(FormBuilderInterface $builder)
    {
        $eventListenerCallable = function(FormEvent $event, $eventName) {
            $this->calledEvents[] = $eventName;
            $this->contextEvents[$eventName][] = [];
            $item = &$this->contextEvents[$eventName][count($this->contextEvents[$eventName]) - 1];

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

    public function testConstantEvent()
    {
        $this->assertEquals('form.pre_set_data', FormEvents::PRE_SET_DATA);
        $this->assertEquals('form.post_set_data', FormEvents::POST_SET_DATA);

        $this->assertEquals('form.pre_bind', FormEvents::PRE_SUBMIT);
        $this->assertEquals('form.bind', FormEvents::SUBMIT);
        $this->assertEquals('form.post_bind', FormEvents::POST_SUBMIT);
    }

    public function testEventsWhenCreateForm()
    {
        $expectedCalledEvents = [
            FormEvents::PRE_SET_DATA => 1,
            FormEvents::POST_SET_DATA => 1,
        ];

        $this->createForm(FormType::class, []);
        $this->assertEquals($expectedCalledEvents, array_count_values($this->calledEvents));
    }

    public function testEventWhenSubmitForm()
    {
        $expectedCalledEvents = [
            FormEvents::PRE_SET_DATA => 1,
            FormEvents::POST_SET_DATA => 1,
            FormEvents::PRE_SUBMIT => 1,
            FormEvents::SUBMIT => 1,
            FormEvents::POST_SUBMIT => 1,
        ];

        $this->createForm(FormType::class, [])->submit([]);

        $this->assertEquals($expectedCalledEvents, array_count_values($this->calledEvents));
    }

    public function testEventWhenSetData()
    {
        $expectedCalledEvents = [
            FormEvents::PRE_SET_DATA => 2,
            FormEvents::POST_SET_DATA => 2,
        ];

        $exceptedDataEvents = [
            FormEvents::PRE_SET_DATA => [
                [
                    'model_data' => null,
                    'normalized_data' => null,
                    'view_data' => null,
                ],
                [
                    'model_data' => [],
                    'normalized_data' => [],
                    'view_data' => [],
                ]
            ],
            FormEvents::POST_SET_DATA => [
                [
                    'model_data' => [],
                    'normalized_data' => [],
                    'view_data' => [],
                ],
                [
                    'model_data' => [],
                    'normalized_data' => [],
                    'view_data' => [],
                ]
            ],

        ];

        $this->createForm(FormType::class, [])->setData([]);

        $this->assertEquals($expectedCalledEvents, array_count_values($this->calledEvents));
        $this->assertEquals($exceptedDataEvents, $this->contextEvents);
    }

}