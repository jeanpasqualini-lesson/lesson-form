<?php
require_once __DIR__.'/vendor/autoload.php';

abstract class PHPUnit_Framework_FakeConstraint extends PHPUnit_Framework_Constraint
{
    private $arguments;
    public static $export = [];

    abstract protected function getConstraintName();

    public function __construct()
    {
        $this->arguments = func_get_args();
    }

    public function evaluate($other, $description = '', $returnResult = false)
    {
        $export = ['export' => false];

        $stack = debug_backtrace(false);
        $location = sprintf(
            '%s:%s:%s (%s).',
            $stack[3]['class'],
            $stack[3]['function'],
            $stack[2]['function'],
            $description
        );
        $lines = file($stack[2]['file']);
        $export['title'] = ['<error>'.$location.'</error>'];
        $export['title'][] = '<info>'.trim(implode(PHP_EOL, array_slice($lines, $stack[2]['line'] - 1, 1))).'</info>';

        switch($this->getConstraintName())
        {
            case PHPUnit_Framework_Constraint_IsEqual::class:
                $value = $this->arguments[0];
                // Si est un tableau de un seul niveau constitué uniquement de chaine
                $yes = true;
                if(is_array($value)) {
                    foreach($value as $elmt) {
                        if(is_array($elmt) || !is_string($elmt)) {
                            $yes = false;
                        }
                    }
                } else {
                    $yes = false;
                }

                if ($yes) {
                    $export['title'][] = '3 erreurs max.';
                    $export['title'][] = 'il y a '.count($value). ' elements à deviner';
                    $reponses = $value;
                    $questions = $reponses;
                    foreach($questions as $id => $question)
                    {
                        $questions[$id] = $question[0].str_repeat('.', strlen($question) - 2).$question[strlen($question) - 1];
                    }
                    $export['question'] = [
                        'type' => 'array_with_one_depth',
                        'reponses' => $reponses,
                        'questions' => $questions
                    ];
                    $export['export'] = true;
                }
                break;

            default:
                break;
        }

        if ($export['export']) {
            self::$export[] = $export;
        }
        return true;
    }

    public function toString() {
        return 'mmmmmmm';
    }
}

class PHPUnit_Framework_Constraint_IsEqual extends PHPUnit_Framework_FakeConstraint {
    protected function getConstraintName() { return __CLASS__; }
}

class TestRunner extends \PHPUnit_TextUI_TestRunner
{
    public static $currentTest;
    /**
     * {@inheritdoc}
     */
    protected function handleConfiguration(array &$arguments)
    {
       $listener = new Class extends PHPUnit_Framework_BaseTestListener {
       };

        $result = parent::handleConfiguration($arguments);
        $arguments['listeners'] = isset($arguments['listeners']) ? $arguments['listeners'] : array();
        $registeredLocally = false;

        if (!$registeredLocally) {
            $arguments['listeners'][] = $listener;
        }
        return $result;
    }
}
class Command extends PHPUnit_TextUI_Command {
    protected function createRunner()
    {
        return new TestRunner($this->arguments['loader']);
    }
}
Command::main(false);
file_put_contents(__DIR__ . '/questions.json', json_encode(PHPUnit_Framework_FakeConstraint::$export, JSON_PRETTY_PRINT));
echo PHP_EOL.' QUESTIONNAIRE GENERER !!! '.PHP_EOL;