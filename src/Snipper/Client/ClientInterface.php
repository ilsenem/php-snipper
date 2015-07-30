<?php namespace Snipper\Client;

/**
 * GitHub API client interface
 */
interface ClientInterface
{
    /**
     * Initialize client with auth token
     *
     * @param string $token
     */
    public function __construct($token);

    /**
     * Get list of gists
     *
     * @return array
     */
    public function getGists();

    /**
     * Get gist by ID
     *
     * @param string $id
     *
     * @return array
     */
    public function getGist($id);
}
