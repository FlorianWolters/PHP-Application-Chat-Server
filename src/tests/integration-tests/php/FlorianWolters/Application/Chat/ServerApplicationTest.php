<?php
/**
 * `ServerApplicationTest.php`
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
 * @category  Application
 * @package   Chat
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2012 Florian Wolters
 * @license   http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @version   GIT: $Id$
 * @link      http://github.com/FlorianWolters/PHP-WebSocket-Chat-Server
 * @since     File available since Release 0.1.0
 */

namespace FlorianWolters\Application\Chat;

use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * Test class for {@link ServerApplication}.
 *
 * @category  Application
 * @package   Chat
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2012 Florian Wolters
 * @license   http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @version   Release: @package_version@
 * @link      http://github.com/FlorianWolters/PHP-WebSocket-Chat-Server
 * @see       ServerApplication
 * @since     Class available since Release 0.1.0
 *
 * @covers    FlorianWolters\Application\Chat\ServerApplication
 */
class ServerApplicationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The {@link ServerApplication} under test.
     *
     * @var ServerApplication
     */
    private $application;

    /**
     * The {@link ApplicationTester} used to test the {@link ServerApplication}.
     *
     * @var ApplicationTester
     */
    private $applicationTester;

    /**
     * Sets up the fixture.
     *
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->application = new ServerApplication;
        $this->applicationTester = new ApplicationTester($this->application);
        $this->applicationTester->run(
            array(
                'command' => 'run',
                '--test' => true
            )
        );
    }

    /**
     * @return void
     *
     * @test
     */
    public function testRun()
    {
        $expected = "Starting chat server...\r\n\r\n"
            . "Waiting for incoming connections on 0.0.0.0:8000...\r\n";
        $display = $this->applicationTester->getDisplay();
        $this->assertEquals($expected, $display);
    }

}
