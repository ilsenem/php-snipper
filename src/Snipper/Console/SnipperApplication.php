<?php namespace Snipper\Console;

use Symfony\Component\Console\Application;

final class SnipperApplication extends Application
{
    public function __construct()
    {
        parent::__construct('Snipper', '0.1.2');

        $this
            ->addCommands([
                new Command\Init,
                new Command\Get,
            ]);
    }
}
