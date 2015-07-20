<?php namespace Snipper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Snipper\Snipper;

final class Get extends Command
{
    protected function configure()
    {
        $this
            ->setName('get')
            ->setDescription('Download Gist snippet and create a new file with snippet\'s content')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Snippet name'
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Overwrite the existing file with the new one'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $snipper = new Snipper;

        $name    = $input->getArgument('name');
        $skipped = $snipper->get($name, $input->getOption('force'));

        if (null === $skipped) {
            return $output->writeLn('<comment>Snippet with name \'' . $name . '\' was not found.</comment>');
        }

        if (is_array($skipped) && count($skipped) > 0) {
            $output->writeLn('<info>Snippet imported successfully, but some files were skipped, as they already exist in the current directory:</info>');

            foreach ($skipped as $file) {
                $output->writeLn("\t" . $file);
            }

            return;
        }

        return $output->writeLn('<info>Snippet imported successfully.</info>');
    }
}
