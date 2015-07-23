<?php namespace Snipper\Tests\Console\Command;

use Snipper\Console\Command\Init;

final class InitTest extends TestCase
{
    public function tearDown()
    {
        $snipperHome   = __DIR__ . DIRECTORY_SEPARATOR . '.snipper';
        $snipperConfig = __DIR__ . DIRECTORY_SEPARATOR . '.snipper' . DIRECTORY_SEPARATOR . 'snipper.json';

        if (is_dir($snipperHome)) {
            if (file_exists($snipperConfig)) {
                unlink($snipperConfig);
            }

            rmdir($snipperHome);
        }
    }

    /**
     * @runInSeparateProcess
     * @expectedException \Exception
     * @expectedExceptionRegExp /home directory must be writable/
     */
    public function testInitThrowsNotWritableHomePath()
    {
        define('OS_HOME_PATH', 'notexists');
        define('SNIPPER_CONFIG_FILE_PATH', null);

        $this->getCommandTester(new Init, 'init', ['token' => 'token']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testInitCreatesSnipperHomeDirectory()
    {
        define('OS_HOME_PATH', __DIR__);
        define('SNIPPER_CONFIG_FILE_PATH', DIRECTORY_SEPARATOR . '.snipper' . DIRECTORY_SEPARATOR . 'snipper.json');

        $tester = $this->getCommandTester(new Init, 'init', ['token' => 'token']);

        $this->assertRegExp('/Done/', $tester->getDisplay());
        $this->assertFileExists(OS_HOME_PATH . SNIPPER_CONFIG_FILE_PATH);
        $this->assertStringEqualsFile(OS_HOME_PATH . SNIPPER_CONFIG_FILE_PATH, json_encode(['token' => 'token'], JSON_PRETTY_PRINT));
    }

    /**
     * @runInSeparateProcess
     */
    public function testInitReplaceTokenIfInitialized()
    {
        define('OS_HOME_PATH', __DIR__);
        define('SNIPPER_CONFIG_FILE_PATH', DIRECTORY_SEPARATOR . '.snipper' . DIRECTORY_SEPARATOR . 'snipper.json');

        $this->getCommandTester(new Init, 'init', ['token' => 'token']);

        $tester = $this->getCommandTester(new Init, 'init', ['token' => 'newtoken']);

        $this->assertRegExp('/Snipper already initialized/', $tester->getDisplay());
        $this->assertStringEqualsFile(OS_HOME_PATH . SNIPPER_CONFIG_FILE_PATH, json_encode(['token' => 'newtoken'], JSON_PRETTY_PRINT));
    }
}
