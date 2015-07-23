<?php namespace Snipper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

final class Init extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Initialize application')
            ->addArgument(
                'token',
                InputArgument::REQUIRED,
                'GitHub personal access token'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $token = $input->getArgument('token');
        $path  = OS_HOME_PATH . SNIPPER_CONFIG_FILE_PATH;

        if (!is_writable(OS_HOME_PATH)) {
            throw new \Exception('Unable to create default configuration due to permission restrictions - home directory must be writable.');
        }

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path));
        }

        if (file_exists($path)) {
            $output->writeLn('Snipper already initialized. Writing new token...');
        }

        file_put_contents($path, json_encode(['token' => $token], JSON_PRETTY_PRINT));

        $output->writeLn('<info>Done.</info>');
    }
}
