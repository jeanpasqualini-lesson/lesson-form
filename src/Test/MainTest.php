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

        echo "On crée le formulaire avec le factory  ...".PHP_EOL;
        $form = $formFactory->create(new OneType(), new OneData());

        echo "On setData manuellement ...".PHP_EOL;
        $form->get("un")->setData("ok");

        echo "On Soumet le formulaire avec submit ...".PHP_EOL;
        $form->submit(array());

        $formView = $form->createView();

        echo PHP_EOL."Rendu HTML : ".PHP_EOL.$twig->render("test.html.twig", array(
                "form" => $formView
            )).PHP_EOL;
    }
}