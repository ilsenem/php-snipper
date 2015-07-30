<?php namespace Snipper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Initialize application
 */
final class Init extends Command
{
    /**
     * @inheritdoc
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Initialize Snipper');
    }

    /**
     * @inheritdoc
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function execute(InputInterface $in, OutputInterface $out)
    {
        $path  = OS_HOME_PATH . SNIPPER_CONFIG_FILE_PATH;

        if (!is_writable(OS_HOME_PATH)) {
            throw new \Exception('Unable to create default configuration due to permission restrictions - home directory must be writable.');
        }

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path));
        }

        $question = new Question('Please enter personal access token for your GitHub account: ');
        $token    = $this->getHelper('question')->ask($in, $out, $question);

        if (file_exists($path)) {
            $out->writeLn('<comment>Snipper already initialized. Writing new token...</comment>');
        }

        file_put_contents($path, json_encode(['token' => $token], JSON_PRETTY_PRINT));

        $out->writeLn('<info>Done.</info>');
    }
}
