<?php namespace Snipper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


final class Get extends Command
{
    protected function configure()
    {
        $this
            ->setName('get')
            ->setDescription('Download Gist snippet and create a new file with it\'s contents')
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Rewrite any file witch have name like snippet have'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //
    }
}
