<?php namespace Snipper\Console;

use Symfony\Component\Console\Application;

use Snipper\Snipper;

final class SnipperApplication extends Application
{
    public function __construct()
    {
        parent::__construct(Snipper::NAME, Snipper::VERSION);

        $this
            ->addCommands([
                new Command\Init,
                new Command\Get,
            ]);
    }
}
