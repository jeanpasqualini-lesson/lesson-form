<?php
/**
 * Created by PhpStorm.
 * User: prestataire
 * Date: 10/11/15
 * Time: 12:01
 */

namespace Form\TypeGuesser;


use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;

class CustomTypeGuesser implements FormTypeGuesserInterface
{
    public function guessType($class, $property)
    {
        return new TypeGuess("integer", array(), Guess::HIGH_CONFIDENCE);
    }

    public function guessRequired($class, $property)
    {
        // TODO: Implement guessRequired() method.
    }

    public function guessMaxLength($class, $property)
    {
        // TODO: Implement guessMaxLength() method.
    }

    public function guessPattern($class, $property)
    {
        // TODO: Implement guessPattern() method.
    }

}