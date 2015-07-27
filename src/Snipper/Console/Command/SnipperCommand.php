<?php namespace Snipper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;

use Snipper\Client\ClientInterface;

abstract class SnipperCommand extends Command
{
    protected $client;

    public function __construct(ClientInterface $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    protected function chooseByIndex($in, $out, $question, $choices)
    {
        $padding = strlen(count($choices)) - 1;

        $out->writeLn($question);

        for ($i = 0; $i < count($choices); $i++) {
            $out->writeLn(sprintf('  [<info>%d</info>] %s', str_pad($i, $padding, ' ', STR_PAD_LEFT), $choices[$i]));
        }

        $question = new Question(' > ');

        // @codeCoverageIgnoreStart
        $question->setValidator(function ($answer) use ($choices) {
            $answer = (int) $answer;

            if (!array_key_exists($answer, $choices)) {
                throw new \RuntimeException(
                    'There is no such option as \'' . $answer . '\'.'
                );
            }

            return $answer;
        });
        // @codeCoverageIgnoreEnd

        $question->setMaxAttempts(3);

        return $this->getHelper('question')->ask($in, $out, $question);
    }
}
