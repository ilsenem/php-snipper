<?php namespace Snipper\Console;

use Symfony\Component\Console\Application;

final class SnipperApplication extends Application
{
    public function __construct()
    {
        parent::__construct(SNIPPER_NAME, SNIPPER_VERSION);

        $this
            ->addCommands([
                new Command\Init,
                new Command\Get,
                // new Command\Save,
            ]);
    }
}
