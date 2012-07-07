<?php
/**
 * `RunServerCommandTest.php`
 *
 * This program is free software: you can redistribute it and/or modify it under
 * the terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see http://gnu.org/licenses/lgpl.txt.
 *
 * PHP version 5.3
 *
 * @category   Application
 * @package    Chat
 * @subpackage Command
 * @author     Florian Wolters <wolters.fl@gmail.com>
 * @copyright  2012 Florian Wolters
 * @license    http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @version    GIT: $Id$
 * @link       http://github.com/FlorianWolters/PHP-WebSocket-Chat-Server
 * @since      File available since Release 0.1.0
 */

namespace FlorianWolters\Application\Chat\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test class for {@link RunServerCommand}.
 *
 * @category   Application
 * @package    Chat
 * @subpackage Command
 * @author     Florian Wolters <wolters.fl@gmail.com>
 * @copyright  2012 Florian Wolters
 * @license    http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @version    Release: @package_version@
 * @link       http://github.com/FlorianWolters/PHP-WebSocket-Chat-Server
 * @see        RunServerCommand
 * @since      Class available since Release 0.1.0
 *
 * @covers     FlorianWolters\Application\Chat\Command\RunServerCommand
 */
class RunServerCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The {@link RunServerCommand} under test.
     *
     * @var RunServerCommand
     */
    private $command;

    /**
     * The arguments to pass to the {@link RunServerCommand}.
     * @var array
     */
    private $commandArguments;

    /**
     * The {@link CommandTester} used to test the {@link RunServerCommand}.
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
     * @covers FlorianWolters\Application\Chat\Command\RunServerCommand::execute
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
     * @covers FlorianWolters\Application\Chat\Command\RunServerCommand::execute
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
     * @covers FlorianWolters\Application\Chat\Command\RunServerCommand::execute
     * @test
     */
    public function testExecuteWithAddressArgument()
    {
        $this->commandArguments['address'] = 'localhost';
        $this->commandTester->execute($this->commandArguments);

        $display = $this->commandTester->getDisplay();

        $expected = 'on localhost:';
        $this->assertContains($expected, $display);
    }

    /**
     * @return array
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
     * @covers FlorianWolters\Application\Chat\Command\RunServerCommand::execute
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
     * @covers FlorianWolters\Application\Chat\Command\RunServerCommand::execute
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
     * @covers FlorianWolters\Application\Chat\Command\RunServerCommand::execute
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
     * @return array
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
     * @covers FlorianWolters\Application\Chat\Command\RunServerCommand::execute
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
