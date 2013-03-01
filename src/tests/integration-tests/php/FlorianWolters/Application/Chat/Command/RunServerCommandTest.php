<?php
namespace FlorianWolters\Application\Chat\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test class for {@see RunServerCommand}.
 *
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2012-2013 Florian Wolters
 * @license   http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @link      http://github.com/FlorianWolters/PHP-Application-Chat-Server
 * @since     Class available since Release 0.1.0
 *
 * @covers    FlorianWolters\Application\Chat\Command\RunServerCommand
 */
class RunServerCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The {@see RunServerCommand} object under test.
     *
     * @var RunServerCommand
     */
    private $command;

    /**
     * The arguments to pass to the {@see RunServerCommand}.
     *
     * @var mixed[]
     */
    private $commandArguments;

    /**
     * The {@see CommandTester} used to test the {@see RunServerCommand}.
     *
     * @var CommandTester
     */
    private $commandTester;

    /**
     * Sets up the fixture.
     *
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $application = new Application;
        $logger = $this->getMockBuilder('Monolog\Logger')
            ->disableOriginalConstructor()
            ->getMock();

        $application->add(new RunServerCommand($logger));
        $this->command = $application->find('run');
        $this->commandArguments = array(
            'command' => $this->command->getName(),
            '--test' => true
        );

        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * @return void
     *
     * @coversDefaultClass execute
     * @test
     */
    public function testExecute()
    {
        $this->commandTester->execute($this->commandArguments);

        $this->isServerStarted();
        $input = $this->commandTester->getInput();
        $this->assertTrue($input->getOption('test'));
    }

    private function isServerStarted()
    {
        $display = $this->commandTester->getDisplay();

        $expected = 'Starting chat server...';
        $this->assertContains($expected, $display);

        $expected = 'Waiting for incoming connections on 0.0.0.0:8000...';
        $this->assertContains($expected, $display);
    }

    /**
     * @return void
     *
     * @coversDefaultClass execute
     * @test
     */
    public function testExecuteWithPortArgument()
    {
        $this->commandArguments['port'] = 65000;
        $this->commandTester->execute($this->commandArguments);

        $display = $this->commandTester->getDisplay();

        $expected = '65000...';
        $this->assertContains($expected, $display);
    }

    /**
     * @return void
     *
     * @coversDefaultClass execute
     * @test
     */
    public function testExecuteWithAddressArgument()
    {
        $this->commandArguments['address'] = '127.0.0.1';
        $this->commandTester->execute($this->commandArguments);

        $display = $this->commandTester->getDisplay();

        $expected = 'on 127.0.0.1:';
        $this->assertContains($expected, $display);
    }

    /**
     * @return string[][]
     */
    public static function providerExecuteWithValidLoglevelOption()
    {
        return array(
            array('DEBUG'),
            array('INFO'),
            array('WARNING'),
            array('ERROR'),
            array('CRITICAL'),
            array('ALERT'),
        );
    }

    /**
     * @param string $logLevel The loglevel.
     *
     * @return void
     *
     * @coversDefaultClass execute
     * @dataProvider providerExecuteWithValidLoglevelOption
     * @test
     */
    public function testExecuteWithValidLoglevelOption($logLevel)
    {
        $this->commandArguments['--loglevel'] = $logLevel;
        $this->commandTester->execute($this->commandArguments);

        $this->isServerStarted();
    }

    /**
     * @return void
     *
     * @coversDefaultClass execute
     * @test
     */
    public function testExecuteWithInvalidLoglevelOption()
    {
        $this->commandArguments['--loglevel'] = 'UNKNOWN';
        $this->commandTester->execute($this->commandArguments);

        $display = $this->commandTester->getDisplay();

        $expected = 'The specified --loglevel option value is invalid.';
        $this->assertContains($expected, $display);
    }

    /**
     * @return void
     *
     * @coversDefaultClass execute
     * @test
     */
    public function testExecuteWithInvalidLogtypeOption()
    {
        $this->commandArguments['--logtype'] = array('UNKNOWN');
        $this->commandTester->execute($this->commandArguments);

        $display = $this->commandTester->getDisplay();

        $expected = 'The specified --logtype option value is invalid.';
        $this->assertContains($expected, $display);
    }

    /**
     * @return string[][]
     */
    public static function providerExecuteWithValidLogtypeOption()
    {
        return array(
            array(array()),
            array(array('STDOUT')),
            array(array('FILE')),
            array(array('STDOUT', 'FILE'))
        );
    }

    /**
     * @param string $logType The logtype.
     *
     * @return void
     *
     * @coversDefaultClass execute
     * @dataProvider providerExecuteWithValidLogtypeOption
     * @test
     */
    public function testExecuteWithValidLogtypeOption($logType)
    {
        $this->commandArguments['--logtype'] = $logType;
        $this->commandTester->execute($this->commandArguments);

        $this->isServerStarted();
    }
}
