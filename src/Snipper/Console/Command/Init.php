<?php namespace Snipper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use Snipper\Snipper;

final class Init extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Initialize application by creating default configuration in home directory')
            ->addArgument(
                'token',
                InputArgument::REQUIRED,
                'GitHub authentication token'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!(new Snipper)->init($input->getArgument('token'))) {
            throw new \Exception('Snipper already initialized.');
        }

        return $output->writeLn('<info>Default configuration created.</info>');
    }
}
