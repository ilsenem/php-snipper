<?php namespace Snipper\Tests\Console;

use Snipper\Console\SnipperApplication;

final class SnipperApplicationTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        define('OS_HOME_PATH', __DIR__ . implode(DIRECTORY_SEPARATOR, ['' ,'..', '..']));
        define('SNIPPER_CONFIG_FILE_PATH', implode(DIRECTORY_SEPARATOR, ['', '.config', 'snipper.json']));
    }

    public function testApplicationStarts()
    {
        $snipper = new SnipperApplication;
    }

    public function testApplicationHasRightName()
    {
        $snipper = new SnipperApplication;

        $this->assertEquals('Snipper', $snipper->getName());
    }

    public function testApplicationHasRightVersion()
    {
        $snipper = new SnipperApplication;

        $this->assertEquals('0.1.4', $snipper->getVersion());
    }

    public function testApplicationHasInitCommand()
    {
        $snipper = new SnipperApplication;

        $this->assertTrue($snipper->has('init'));
        $this->assertInstanceOf('\Snipper\Console\Command\Init', $snipper->get('init'));
    }

    public function testApplicationHasGetCommand()
    {
        $snipper = new SnipperApplication;

        $this->assertTrue($snipper->has('get'));
        $this->assertInstanceOf('\Snipper\Console\Command\Get', $snipper->get('get'));
    }
}
