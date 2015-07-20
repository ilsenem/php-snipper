<?php namespace Snipper;

final class Util
{
    private $win = false;

    public function __construct()
    {
        if (stripos(PHP_OS, 'win') && PHP_OS !== 'Darwin') {
            $this->win = true;
        }
    }

    public function getHomePath()
    {
        return ($this->win) ? getenv('HOMEDRIVE') . getenv('HOMEPATH') : getenv('HOME');
    }

    public function getSnipperHomePath()
    {
        return $this->getHomePath() . DIRECTORY_SEPARATOR . '.snipper';
    }

    public function getConfigPath()
    {
        return $this->getSnipperHomePath() . DIRECTORY_SEPARATOR . 'snipper.json';
    }

    public function getCachePath()
    {
        return $this->getSnipperHomePath() . DIRECTORY_SEPARATOR . 'cache';
    }
}
