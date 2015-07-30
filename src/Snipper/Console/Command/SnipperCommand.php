<?php namespace Snipper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Snipper\Client\ClientInterface;

/**
 * Abstract command class for Snipper application
 */
abstract class SnipperCommand extends Command
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * Bootstrap application with GitHub client
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * Ask user to choose from list of available variants and return selected
     * index
     *
     * @param InputInterface  $in
     * @param OutputInterface $out
     * @param string          $question
     * @param array           $choices
     *
     * @return mixed
     */
    protected function chooseByIndex(InputInterface $in, OutputInterface $out, $question, array $choices)
    {
        $padding = strlen(count($choices)) - 1;

        $out->writeLn($question);

        for ($i = 0; $i < count($choices); $i++) {
            $out->writeLn(sprintf('  [<info>%d</info>] %s', str_pad($i, $padding, ' ', STR_PAD_LEFT), $choices[$i]));
        }

        $questionResolver = new Question(' > ');

        // @codeCoverageIgnoreStart
        $questionResolver->setValidator(function ($answer) use ($choices) {
            $answer = (int) $answer;

            if (!array_key_exists($answer, $choices)) {
                throw new \RuntimeException(
                    'There is no such option as \'' . $answer . '\'.'
                );
            }

            return $answer;
        });
        // @codeCoverageIgnoreEnd

        $questionResolver->setMaxAttempts(3);

        return $this->getHelper('question')->ask($in, $out, $questionResolver);
    }
}
