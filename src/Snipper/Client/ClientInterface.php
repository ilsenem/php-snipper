<?php namespace Snipper\Client;

interface ClientInterface
{
    public function __construct($token);
    public function getGists();
    public function getGist($id);
}
