<?php

namespace Traits;

use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait FormTypeTestTrait
{
    private function assertFormHasOptionsAvailable($expected, $class)
    {
        $optionsAvailable = $this->getOptionsAvailable($class);
        sort($expected);
        sort($optionsAvailable);

        $doc = sprintf(
            'http://symfony.com/doc/current/reference/forms/types/%s.html',
            strtolower(str_replace(['Symfony\Component\Form\Extension\Core\Type\\', 'Type'], '', $class))
        );

        $this->assertEquals($expected, $optionsAvailable, ''.$class. PHP_EOL. '('.$doc.')');
    }

    private function getOptionsAvailable($class)
    {
        $optionResolver = new OptionsResolver();
        /** @var FormTypeInterface $type */
        $type = new $class();
        $type->configureOptions($optionResolver);

        if($type->getParent() !== null)
        {
            return array_diff(
                $optionResolver->getDefinedOptions(),
                $this->getOptionsAvailable($type->getParent())
            );
        } elseif (FormType::class !== $class) {
            return array_diff(
                $optionResolver->getDefinedOptions(),
                $this->getOptionsAvailable(FormType::class)
            );
        }

        return $optionResolver->getDefinedOptions();
    }
}