<?php
namespace Test;

use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class MainTest extends \PHPUnit_Framework_TestCase
{
    private function getTraceableDispatcher()
    {
        $eventDispatcher = new Class(new EventDispatcher(), new Stopwatch()) extends TraceableEventDispatcher {
            public function hasListeners($eventName = null)
            {
                return true;
            }
        };
        $eventDispatcher->addListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {});
        $eventDispatcher->addListener(FormEvents::POST_SET_DATA, function(FormEvent $event) {});
        $eventDispatcher->addListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {});
        $eventDispatcher->addListener(FormEvents::SUBMIT, function(FormEvent $event) {});
        $eventDispatcher->addListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {});

        return $eventDispatcher;
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
            FormEvents::PRE_SET_DATA,
            FormEvents::POST_SET_DATA
        ];

        $formFactory = $this->getMockBuilder(FormFactoryInterface::class)->getMock();
        $eventDispatcher = $this->getTraceableDispatcher();

        $formBuilder = new FormBuilder(
            'form',
            null,
            $eventDispatcher,
            $formFactory
        );
        $formBuilder->getForm()->setData([]);

        $calledEvents = array_column($eventDispatcher->getCalledListeners(), 'event');
        $this->assertEquals($expectedCalledEvents, $calledEvents);
    }

}