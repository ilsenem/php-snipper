<?php namespace Snipper\Tests\Console\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{

    protected function getCommandTester(Command $command, $name, $params = [])
    {
        $application = new Application;
            $application->add($command);

        $tester = new CommandTester($application->get($name));
            $tester->execute(array_merge(['command' => $command->getName(),], $params));

        return $tester;
    }
}
