<?php
namespace Test;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryBuilder;

class EventDeclanchedTest extends \PHPUnit_Framework_TestCase
{
    protected $calledEvents;

    public function setUp()
    {
        $this->calledEvents = [];
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

    private function registerTraceableListener(FormBuilderInterface $builder)
    {
        $eventListenerCallable = function(FormEvent $event, $eventName) {
            $this->calledEvents[] = $eventName;
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

        $this->createForm(FormType::class, ['title' => 'red']);
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

        $this->createForm(FormType::class, ['title' => 'red'])->submit(['title' => 'green']);

        $this->assertEquals($expectedCalledEvents, array_count_values($this->calledEvents));
    }

    public function testEventWhenSetData()
    {
        $expectedCalledEvents = [
            FormEvents::PRE_SET_DATA => 1,
            FormEvents::POST_SET_DATA => 1,
        ];

        $this->createForm(FormType::class, ['title' => 'red'])->setData(['title' => 'green']);
        $this->assertEquals($expectedCalledEvents, array_count_values($this->calledEvents));
    }

    public function testEventWhenSetDataWithCasePreset()
    {
        // When data locked, event set data not trigger
        $expectedCalledEvents = [
            FormEvents::PRE_SET_DATA => 4, // ??
            FormEvents::POST_SET_DATA => 4, // ??
        ];

        $this->createForm(FormType::class, null)
            ->setData(['title' => 'green'])
            ->setData([])
            ->setData(['title' => 'green']);
        $this->assertEquals($expectedCalledEvents, array_count_values($this->calledEvents));
    }

    public function testDataLocked()
    {
        // When data locked, event set data not trigger
        $form = $this->createForm(FormType::class, ['title' => 'red']);
        $this->assertEquals(true, $form->getConfig()->getDataLocked());

        $form = $this->createForm(FormType::class, null);
        $this->assertEquals(false, $form->getConfig()->getDataLocked());

        $form = $this->createForm(FormType::class, null)->setData(['title' => 'red']);
        $this->assertEquals(false, $form->getConfig()->getDataLocked());
    }
}