<?php namespace Snipper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;

use Github\Client;
use Github\HttpClient\CachedHttpClient;

abstract class SnipperCommand extends Command
{
    protected function getClient()
    {
        $config = $this->getConfig();

        if (empty($config['token'])) {
            throw new \Exception('GitHub personal authentication token was not found in configuration file - run \'snipper init\' to set token.');
        }

        $cacheDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'snipper';

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir);
        }

        $client = new Client(new CachedHttpClient(['cache_dir' => $cacheDir]));
            $client->authenticate($config['token'], Client::AUTH_HTTP_TOKEN);

        return $client;
    }

    protected function getConfig()
    {
        if (!file_exists(OS_HOME_PATH . SNIPPER_CONFIG_FILE_PATH)) {
            throw new \Exception('Configuration file not found. Is Snipper initialized?');
        }

        $config = json_decode(file_get_contents(OS_HOME_PATH . SNIPPER_CONFIG_FILE_PATH), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception('Unable to parse configuration file: ' . json_last_error_msg() . '.');
        }

        return $config;
    }

    protected function chooseByIndex($in, $out, $question, $choices)
    {
        $padding = strlen(count($choices)) - 1;

        $out->writeLn($question);

        for ($i = 0; $i < count($choices); $i++) {
            $out->writeLn(sprintf('  [<info>%d</info>] %s', str_pad($i, $padding, ' ', STR_PAD_LEFT), $choices[$i]));
        }

        $question = new Question(' > ');
            $question->setValidator(function ($answer) use ($choices) {
                $answer = (int) $answer;

                if (!array_key_exists($answer, $choices)) {
                    throw new \RuntimeException(
                        'There is no such option as \'' . $answer . '\'.'
                    );
                }

                return $answer;
            });
            $question->setMaxAttempts(3);

        return $this->getHelper('question')->ask($in, $out, $question);
    }
}
