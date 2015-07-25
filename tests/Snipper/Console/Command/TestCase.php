<?php namespace Snipper\Tests\Console\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

use Snipper\Tests\Mocks\ClientMock;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function getCommandTester(Command $command, $name, $params = [], $helpers = [])
    {
        $application = new Application;
            $application->add($command);

        $testedCommand = $application->get($name);

        if (!empty($helpers)) {
            foreach ($helpers as $helperName => $helperMock) {
                $testedCommand->getHelperSet()->set($helperMock, $helperName);
            }
        }

        $tester = new CommandTester($testedCommand);
            $tester->execute(array_merge(['command' => $command->getName(),], $params));

        return $tester;
    }

    protected function getClientMock()
    {
        $client = new ClientMock('');
            $client->setGists([
                [
                    'id'          => 0,
                    'description' => '#snippet',
                    'files'       => [
                        'test' => [
                            'size'    => 123,
                            'content' => 'Test snippet',
                        ],
                    ],
                ],
                [
                    'id'          => 1,
                    'description' => '#anothersnippet',
                    'files'       => [
                        'test' => [
                            'size'    => 321,
                            'content' => 'Another test',
                        ],
                    ],
                ],
                [
                    'id'          => 2,
                    'description' => '#duplicate',
                    'files'       => [
                        'test' => [
                            'size'    => 123,
                            'content' => 'First duplicate',
                        ],
                    ],
                ],
                [
                    'id'          => 3,
                    'description' => '#duplicate',
                    'files'       => [
                        'test' => [
                            'size'    => 321,
                            'content' => 'Second duplicate',
                        ],
                    ],
                ],
            ]);

        return $client;
    }
}
