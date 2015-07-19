<?php namespace Snipper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


final class Init extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Initialize application by creating default configuration in home directory')
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force to rewrite existing file with default values'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //
    }
}
