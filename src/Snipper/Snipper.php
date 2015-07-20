<?php namespace Snipper;

use Github\Client;
use Github\HttpClient\CachedHttpClient;

use Snipper\Util;

final class Snipper
{
    const NAME    = 'Snipper';
    const VERSION = '0.1.0';

    private $util;
    private $initialized;
    private $client;

    public function __construct()
    {
        $this->util        = new Util;
        $this->initialized = $this->isInitialized();

        if ($this->initialized) {
            $config = $this->getConfig();

            if (!isset($config['token'])) {
                throw new Exception('Authentication token is required for Snipper to work properly.');
            }

            $this->client = new Client(new CachedHttpClient(array('cache_dir' => $this->util->getCachePath())));
                $this->client->authenticate($config['token'], null, Client::AUTH_HTTP_TOKEN);
        }
    }

    public function init($token)
    {
        if ($this->initialized) {
            return false;
        }

        if (!is_writable($this->util->getHomePath())) {
            throw new \Exception('Unable to create default configuration due to permission restrictions.');
        }

        $defaultConfig = realpath(__DIR__ . '/../..') . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'snipper.json';

        if (!mkdir($this->util->getCachePath(), 0777, true) || !copy($defaultConfig, $this->util->getConfigPath())) {
            throw new \Exception('Unable to create default configuration. Check permission rights.');
        }

        file_put_contents($this->util->getConfigPath(), str_replace('%token%', '"' . $token . '"', file_get_contents($this->util->getConfigPath())));

        return true;
    }

    public function get($name, $force = false)
    {
        if (!$this->initialized) {
            $this->showNotInitializedError();
        }

        $gists = $this->client->api('gist')->all('starred');

        foreach ($gists as $gist) {
            if (strpos($gist['description'], '#' . $name) !== false) {
                return $this->saveGistContent($gist, $force);
            }
        }

        return null;
    }

    private function isInitialized()
    {
        return (
            file_exists($this->util->getCachePath())
            && is_dir($this->util->getCachePath())
            && file_exists($this->util->getConfigPath())
        );
    }

    private function getConfig()
    {
        return json_decode(file_get_contents($this->util->getConfigPath()), true);
    }

    private function saveGistContent(array $gist, $force = false)
    {
        $dir = getcwd();

        if (!is_writable($dir)) {
            throw new \Exception('Unable to save snippet in current directory. Check permission rights.');
        }

        $gist    = $this->client->api('gist')->show($gist['id']);
        $skipped = [];

        foreach ($gist['files'] as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file['filename'];

            if (!$force && file_exists($path)) {
                $skipped[] = $file['filename'];

                continue;
            }

            file_put_contents($path, $file['content']);
        }

        return $skipped;
    }

    private function showNotInitializedError()
    {
        throw new \Exception('Snipper is not initialized. Run \'init\' to initialize application.');
    }
}
