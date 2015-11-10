<?php
/**
 * Created by PhpStorm.
 * User: prestataire
 * Date: 10/11/15
 * Time: 10:30
 */

namespace Loader;
use Symfony\Component\Templating\Loader\Loader;
use Symfony\Component\Templating\Storage\FileStorage;
use Symfony\Component\Templating\Storage\Storage;
use Symfony\Component\Templating\TemplateReferenceInterface;

class TemplateLoader extends Loader
{
    public function load(TemplateReferenceInterface $template)
    {

        $path = str_replace("FrameworkBundle:Form:", "", $template->getPath());

        return new FileStorage(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."template".DIRECTORY_SEPARATOR.$path);
        // TODO: Implement load() method.
    }

    public function isFresh(TemplateReferenceInterface $template, $time)
    {
        return false;
    }

}