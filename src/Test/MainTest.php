<?php
/**
 * Created by PhpStorm.
 * User: prestataire
 * Date: 09/11/15
 * Time: 17:28
 */

namespace Test;

use ClassExample\OneData;
use ClassExample\OneType;
use Environement\Test;
use \Interfaces\TestInterface;


// http://symfony.com/doc/current/components/form/form_events.html
class MainTest implements TestInterface
{
    protected $environement;

    public function init()
    {
        $this->environement = new Test();
    }

    public function __construct()
    {
        $this->init();
    }

    public function runTest()
    {
        $formFactory = $this->environement->getFormFactory();

        $twig = $this->environement->getTwig();

        $form = $formFactory->create(new OneType(), new OneData());

        $formView = $form->createView();

        echo PHP_EOL.$twig->render("test.html.twig", array(
                "form" => $formView
            )).PHP_EOL;
    }
}