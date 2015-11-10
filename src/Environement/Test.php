<?php
namespace Environement;

use Form\TypeGuesser\CustomTypeGuesser;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Forms;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;


/**
 * Created by PhpStorm.
 * User: prestataire
 * Date: 10/11/15
 * Time: 11:47
 */
class Test
{
    protected $formFactory;

    protected $twig;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $twig = $this->createTwig();

        $this->configureTrans($twig);

        $this->configureForm($twig);

        $formFactory = Forms::createFormFactoryBuilder()
            ->addTypeGuesser(new CustomTypeGuesser())
            ->getFormFactory();

        $this->formFactory = $formFactory;

        $this->twig = $twig;
    }

    public function getFormFactory()
    {
        return $this->formFactory;
    }

    public function getTwig()
    {
        return $this->twig;
    }


    public function configureTrans(\Twig_Environment $twig)
    {
        $translator = new Translator('en');
        // somehow load some translations into it
        $translator->addLoader('xlf', new XliffFileLoader());
        // add the TranslationExtension (gives us trans and transChoice filters)
        $twig->addExtension(new TranslationExtension($translator));
    }

    public function configureForm(\Twig_Environment $twig)
    {
        // the Twig file that holds all the default markup for rendering forms
        // this file comes with TwigBridge
        $defaultFormTheme = 'form_div_layout.html.twig';

        $formEngine = new TwigRendererEngine(array($defaultFormTheme));

        $formEngine->setEnvironment($twig);

        // add the FormExtension to Twig
        $twig->addExtension(
            new FormExtension(new TwigRenderer($formEngine, null))
        );
    }

    public function createTwig()
    {
        $vendorDir = realpath(__DIR__.'/../vendor');

        // the path to TwigBridge library so Twig can locate the
        // form_div_layout.html.twig file
        $appVariableReflection = new \ReflectionClass('\Symfony\Bridge\Twig\AppVariable');
        $vendorTwigBridgeDir = dirname($appVariableReflection->getFileName());

        // the path to your other templates
        $viewsDir = realpath(__DIR__.'/../template');

        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem(array(
            $viewsDir,
            $vendorTwigBridgeDir.'/Resources/views/Form',
        )));

        return $twig;
    }
}