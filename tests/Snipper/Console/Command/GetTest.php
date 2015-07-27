<?php namespace Snipper\Tests\Console\Command;

use Snipper\Console\Command\Get;

final class GetTest extends TestCase
{
    private $testDir = __DIR__ . DIRECTORY_SEPARATOR . 'snippets';

    public function setUp()
    {
        parent::setUp();

        mkdir($this->testDir);
        chdir($this->testDir);
    }

    public function tearDown() {
        parent::tearDown();

        if (is_dir($this->testDir)) {
            $files = array_diff(scandir($this->testDir), array('..', '.'));

            if (!empty($files)) {
                foreach ($files as $file) {
                    unlink($this->testDir . DIRECTORY_SEPARATOR . $file);
                }
            }

            rmdir($this->testDir);
        }
    }

    public function testGetSnippetNotFound()
    {
        $tester = $this->getCommandTester(new Get($this->getClientMock()), 'get', [
            'name' => 'notfound',
        ]);

        $this->assertRegExp('/not found/', $tester->getDisplay());
    }

    public function testGetFindAndSaveSnippet()
    {
        $tester = $this->getCommandTester(new Get($this->getClientMock()), 'get', [
            'name' => 'snippet',
        ]);

        $this->assertRegExp('/New files.*test/s', $tester->getDisplay());
        $this->assertFileExists($this->testDir . DIRECTORY_SEPARATOR . 'test');
    }

    public function testGetNotOverwriteSnippet()
    {
        $client = $this->getClientMock();

        $this->getCommandTester(new Get($client), 'get', [
            'name' => 'snippet',
        ]);

        $tester = $this->getCommandTester(new Get($client), 'get', [
            'name' => 'anothersnippet',
        ]);

        $this->assertRegExp('/Skipped files.*test/s', $tester->getDisplay());
        $this->assertFileExists($this->testDir . DIRECTORY_SEPARATOR . 'test');
        $this->assertStringEqualsFile($this->testDir . DIRECTORY_SEPARATOR . 'test', 'Test snippet');
    }

    public function testGetOverwriteSnippet()
    {
        $client = $this->getClientMock();

        $this->getCommandTester(new Get($client), 'get', [
            'name' => 'snippet',
        ]);

        $tester = $this->getCommandTester(new Get($client), 'get', [
            'name' => 'anothersnippet',
            '-f'   => true,
        ]);

        $this->assertRegExp('/Overwritten files.*test/s', $tester->getDisplay());
        $this->assertFileExists($this->testDir . DIRECTORY_SEPARATOR . 'test');
        $this->assertStringEqualsFile($this->testDir . DIRECTORY_SEPARATOR . 'test', 'Another test');
    }

    public function testGetAskAboutDuplicates()
    {
        $questionHelperMock = $this->getMock(
            '\Symfony\Component\Console\Helper\QuestionHelper',
            ['ask']
        );

        $questionHelperMock
            ->method('ask')
            ->willReturn(1);

        $tester = $this->getCommandTester(new Get($this->getClientMock()), 'get', [
            'name' => 'duplicate'
        ], [
            'question' => $questionHelperMock,
        ]);

        $this->assertRegExp('/New files.*test/s', $tester->getDisplay());
        $this->assertFileExists($this->testDir . DIRECTORY_SEPARATOR . 'test');
        $this->assertStringEqualsFile($this->testDir . DIRECTORY_SEPARATOR . 'test', 'Second duplicate');
    }
}
