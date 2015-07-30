<?php namespace Snipper\Client;

use Github\Client;
use Github\HttpClient\CachedHttpClient;

/**
 * ClientInterface implementation of Knplabs GitHub API client
 *
 * @see https://github.com/KnpLabs/php-github-api
 */
final class KnplabsClient implements ClientInterface
{
    /**
     * @var \Github\Client
     */
    private $client;

    /**
     * @inheritdoc
     */
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
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function getGists()
    {
        return $this->client->api('gists')->all('starred');
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function getGist($id)
    {
        return $this->client->api('gists')->show($id);
    }
}
