<?php
require_once __DIR__.'/vendor/autoload.php';
$symfonyStyle = new \Symfony\Component\Console\Style\SymfonyStyle(new \Symfony\Component\Console\Input\ArrayInput([]), new \Symfony\Component\Console\Output\ConsoleOutput());

$export = json_decode(file_get_contents(__DIR__ . '/questions.json'), true);

foreach($export as $exportItem) {
    if(isset($exportItem['question']))
    {
        switch($exportItem['question']['type'])
        {
            case 'array_with_one_depth':
                $questions = $exportItem['question']['questions'];
                $reponses = $exportItem['question']['reponses'];

                $symfonyStyle->writeln(implode(PHP_EOL, $exportItem['title']));
                $errorCount = 0;
                foreach($questions as $id => $question)
                {
                    if($errorCount >= 3)
                    {
                        $symfonyStyle->error('VOUS AVEZ PERDU');
                        break;
                    }

                    $userReponse = $symfonyStyle->ask($question. ' ?');
                    if($userReponse == $reponses[$id])
                    {
                        $symfonyStyle->success('OUI');
                    } else {
                        $symfonyStyle->error('NON C\'ETAIS \''.$reponses[$id].'\'');
                        $errorCount ++;
                    }
                }
                break;
        }
    }

}

