<?php namespace Snipper\Tests\Console;

use Snipper\Console\SnipperApplication;

final class SnipperApplicationTest extends \PHPUnit_Framework_TestCase
{
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

        $this->assertEquals('0.1.2', $snipper->getVersion());
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
