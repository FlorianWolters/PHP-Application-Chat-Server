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
 * PHP version 5.4
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
 * The {@link RunServerCommand} starts the chat server.
 *
 * @category   Application
 * @package    Chat
 * @subpackage Command
 * @author     Florian Wolters <wolters.fl@gmail.com>
 * @copyright  2012 Florian Wolters
 * @license    http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @version    Release: @package_version@
 * @link       http://github.com/FlorianWolters/PHP-WebSocket-Chat-Server
 * @see        RunServerCommandTest
 * @since      Class available since Release 0.1.0
 *
 * @covers FlorianWolters\Application\Chat\Command\RunServerCommand
 */
class RunServerCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     *
     * @covers FlorianWolters\Application\Chat\Command\RunServerCommand::execute
     * @test
     */
    public function testExecute()
    {
        $application = new Application;
        $logger = $this->getMockBuilder('Monolog\Logger')
            ->disableOriginalConstructor()
            ->getMock();

        $application->add(new RunServerCommand($logger));

        $command = $application->find('run');
        $commandTester = new CommandTester($command);

        // TODO Can not run test, since RunServerCommand is blocking.
        //$commandTester->execute(array('command' => $command->getName()));
        //$this->assertRegExp('/.../', $commandTester->getDisplay());
    }

}
