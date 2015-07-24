<?php namespace Snipper\Tests\Mocks;

use Snipper\Client\ClientInterface;

final class ClientMock implements ClientInterface
{
    private $gists;

    public function __construct($token)
    {
        // Authenticated
    }

    public function getGists()
    {
        return $this->gists;
    }

    public function getGist($id)
    {
        return (isset($this->gists[$id])) ? $this->gists[$id] : [];
    }

    public function setGists(array $gists)
    {
        $this->gists = $gists;

        return $this;
    }
}
