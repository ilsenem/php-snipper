<?php namespace Snipper\Console;

use Symfony\Component\Console\Application;

use Snipper\Client\KnplabsClient;

final class SnipperApplication extends Application
{
    private $config;

    public function __construct()
    {
        parent::__construct('Snipper', '0.1.4');

        $this->getConfig();

        if (empty($this->config['token'])) {
            throw new \Exception('GitHub personal access token is required for application to work. Please run \'snipper init <token>\' to add one.');
        }

        $client = new KnplabsClient($this->config['token']);

        $this
            ->addCommands([
                new Command\Init,
                new Command\Get($client),
            ]);
    }

    protected function getConfig()
    {
        // @codeCoverageIgnoreStart
        if (!is_null($this->config)) {
            return $this->config;
        }
        // @codeCoverageIgnoreEnd

        if (!file_exists(OS_HOME_PATH . SNIPPER_CONFIG_FILE_PATH)) {
            throw new \Exception('Configuration file not found. Is Snipper initialized?');
        }

        $config = json_decode(file_get_contents(OS_HOME_PATH . SNIPPER_CONFIG_FILE_PATH), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception('Unable to parse configuration file: ' . json_last_error_msg() . '.');
        }

        return $this->config = $config;
    }
}
