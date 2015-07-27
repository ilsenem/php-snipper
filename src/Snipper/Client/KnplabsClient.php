<?php namespace Snipper\Client;

use Github\Client;
use Github\HttpClient\CachedHttpClient;

final class KnplabsClient implements ClientInterface
{
    private $client;

    public function __construct($token)
    {
        $cacheDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'snipper';

        // @codeCoverageIgnoreStart
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir);
        }
        // @codeCoverageIgnoreEnd

        $this->client = new Client(new CachedHttpClient(['cache_dir' => $cacheDir]));
        $this->client->authenticate($token, Client::AUTH_HTTP_TOKEN);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getGists()
    {
        return $this->client->api('gists')->all('starred');
    }

    /**
     * @codeCoverageIgnore
     */
    public function getGist($id)
    {
        return $this->client->api('gists')->show($id);
    }
}
