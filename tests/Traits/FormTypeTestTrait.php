<?php

namespace Traits;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait FormTypeTestTrait
{
    private function assertFormHasOptionsAvailable($expected, $class)
    {
        $optionsAvailable = $this->getOptionsAvailable($class);
        sort($expected);
        sort($optionsAvailable);

        $this->assertEquals($expected, $optionsAvailable, 'assert options of '.$class);
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
        }

        return $optionResolver->getDefinedOptions();
    }
}