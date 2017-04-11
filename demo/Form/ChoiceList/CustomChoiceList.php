<?php
/**
 * Created by PhpStorm.
 * User: prestataire
 * Date: 10/11/15
 * Time: 15:29
 */

namespace Form\ChoiceList;


use Symfony\Component\Form\ChoiceList\ChoiceListInterface;

class CustomChoiceList implements ChoiceListInterface
{
    public function getChoices()
    {
       return array(
           "un",
           "duex",
           "trois"
       );
    }

    public function getValues()
    {
        // TODO: Implement getValues() method.
    }

    public function getStructuredValues()
    {
        // TODO: Implement getStructuredValues() method.
    }

    public function getOriginalKeys()
    {
        // TODO: Implement getOriginalKeys() method.
    }

    public function getChoicesForValues(array $values)
    {
        // TODO: Implement getChoicesForValues() method.
    }

    public function getValuesForChoices(array $choices)
    {
        // TODO: Implement getValuesForChoices() method.
    }

}