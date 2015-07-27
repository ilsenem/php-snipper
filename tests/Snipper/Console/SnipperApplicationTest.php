<?php namespace Snipper\Tests\Console;

use Snipper\Console\SnipperApplication;

final class SnipperApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testApplicationStarts()
    {
        $this->setDefaultPaths();

        $snipper = new SnipperApplication;
    }

    /**
     * @runInSeparateProcess
     */
    public function testApplicationHasRightName()
    {
        $this->setDefaultPaths();

        $snipper = new SnipperApplication;

        $this->assertEquals('Snipper', $snipper->getName());
    }

    /**
     * @runInSeparateProcess
     */
    public function testApplicationHasRightVersion()
    {
        $this->setDefaultPaths();

        $snipper = new SnipperApplication;

        $this->assertEquals('0.1.4', $snipper->getVersion());
    }

    /**
     * @runInSeparateProcess
     */
    public function testApplicationHasInitCommand()
    {
        $this->setDefaultPaths();

        $snipper = new SnipperApplication;

        $this->assertTrue($snipper->has('init'));
        $this->assertInstanceOf('\Snipper\Console\Command\Init', $snipper->get('init'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testApplicationHasGetCommand()
    {
        $this->setDefaultPaths();

        $snipper = new SnipperApplication;

        $this->assertTrue($snipper->has('get'));
        $this->assertInstanceOf('\Snipper\Console\Command\Get', $snipper->get('get'));
    }

    /**
     * @runInSeparateProcess
     * @expectedException \Exception
     * @expectedExceptionMessageRegExp /file not found/
     */
    public function testApplicationThrowsConfigNotFound()
    {
        define('OS_HOME_PATH', __DIR__ . implode(DIRECTORY_SEPARATOR, ['' ,'..', '..']));
        define('SNIPPER_CONFIG_FILE_PATH', implode(DIRECTORY_SEPARATOR, ['', '.config', 'snipper.missed']));

        $snipper = new SnipperApplication;
    }

    /**
     * @runInSeparateProcess
     * @expectedException \Exception
     * @expectedExceptionMessageRegExp /^Unable to parse/
     */
    public function testApplicationThrowsConfigWrongFormat()
    {
        define('OS_HOME_PATH', __DIR__ . implode(DIRECTORY_SEPARATOR, ['' ,'..', '..']));
        define('SNIPPER_CONFIG_FILE_PATH', implode(DIRECTORY_SEPARATOR, ['', '.config', 'snipper.wrong.json']));

        $snipper = new SnipperApplication;
    }

    /**
     * @runInSeparateProcess
     * @expectedException \Exception
     * @expectedExceptionMessageRegExp /personal access token is required/
     */
    public function testApplicationThrowsConfigNoToken()
    {
        define('OS_HOME_PATH', __DIR__ . implode(DIRECTORY_SEPARATOR, ['' ,'..', '..']));
        define('SNIPPER_CONFIG_FILE_PATH', implode(DIRECTORY_SEPARATOR, ['', '.config', 'snipper.empty.json']));

        $snipper = new SnipperApplication;
    }

    private function setDefaultPaths()
    {
        define('OS_HOME_PATH', __DIR__ . implode(DIRECTORY_SEPARATOR, ['' ,'..', '..']));
        define('SNIPPER_CONFIG_FILE_PATH', implode(DIRECTORY_SEPARATOR, ['', '.config', 'snipper.json']));
    }
}
