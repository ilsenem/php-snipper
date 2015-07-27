<?php namespace Snipper\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

use Snipper\Client\ClientInterface;

final class Get extends SnipperCommand
{
    protected function configure()
    {
        $this
            ->setName('get')
            ->setDescription('Get snippet from GitHub Gist service')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Snippet name (without hash sign)'
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Overwrite existing file'
            );
    }

    protected function execute(InputInterface $in, OutputInterface $out)
    {
        $name   = $in->getArgument('name');
        $force  = $in->getOption('force');
        $gists  = $this->client->getGists();

        $found = array_filter($gists, function ($gist) use ($name) {
            $tag = '#' . $name;

            return strpos($gist['description'], $tag) !== false;
        });

        $found = array_values($found);

        switch (count($found)) {
            case 0:
                return $out->writeLn('<comment>Snippet with the name \'' . $name . '\' was not found.</comment>');
            case 1:
                $gist = $this->client->getGist($found[0]['id']);
                break;
            default:
                $choices = [];

                foreach ($gists as $gist) {
                    $description = trim(str_replace('#' . $name, '', $gist['description']));

                    $choices[] = sprintf('<options=bold>%s</options=bold> - %s', $name, $description);
                }

                $index = $this->chooseByIndex(
                    $in, $out,
                    'Snipper found duplicate snippets. Please select which to import:',
                    $choices
                );

                $gist = $this->client->getGist($found[$index]['id']);
                break;
        }

        return $this->saveSnippet($in, $out, $gist['files'], $force);
    }

    private function saveSnippet(InputInterface $in, OutputInterface $out, array $snippet, $force = false)
    {
        $cwd         = getcwd();
        $imported    = [];
        $skipped     = [];
        $overwritten = [];

        $filesize = function ($bytes, $dec = 2) {
            $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
            $factor = floor((strlen($bytes) - 1) / 3);

            return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . $size[$factor];
        };

        foreach ($snippet as $filename => $data) {
            $filepath = $cwd . DIRECTORY_SEPARATOR . $filename;

            if (file_exists($filepath)) {
                $string = sprintf('  <options=bold>%s</options=bold> (%s -> %s)', $filename, $filesize(filesize($filepath)), $filesize($data['size']));

                if (!$force) {
                    $skipped[] = $string;

                    continue;
                }

                $overwritten[] = $string;
            } else {
                $imported[] = sprintf('  <options=bold>%s</options=bold> (%s)', $filename, $filesize($data['size']));
            }

            file_put_contents($filepath, $data['content']);
        }

        if (!empty($imported)) {
            $out->writeLn('New files:');

            foreach ($imported as $string) {
                $out->writeLn($string);
            }
        }

        if (!empty($skipped)) {
            $out->writeLn('Skipped files, since they are already exist (use \'-f\' to overwrite files):');

            foreach ($skipped as $string) {
                $out->writeLn($string);
            }
        }

        if (!empty($overwritten)) {
            $out->writeLn('Overwritten files:');

            foreach ($overwritten as $string) {
                $out->writeLn($string);
            }
        }

        return $out->writeLn('<info>Done.</info>');
    }
}
