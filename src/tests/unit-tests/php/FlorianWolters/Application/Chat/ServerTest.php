<?php
/**
 * `ServerTest.php`
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

/**
 * Test class for {@link Server}.
 *
 * @category  Application
 * @package   Chat
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2012 Florian Wolters
 * @license   http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @version   Release: @package_version@
 * @link      http://github.com/FlorianWolters/PHP-WebSocket-Chat-Server
 * @see       Server
 * @since     Class available since Release 0.1.0
 * @todo      Add more complex test cases.
 *
 * @covers    FlorianWolters\Application\Chat\Server
 */
class ServerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The {@link Server} under test.
     *
     * @var Server
     */
    private $server;

    /**
     * The mocked {@link ConnectionInterface}.
     *
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * Sets up the fixture.
     *
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $logger = $this->getMockBuilder('Monolog\Logger')
            ->disableOriginalConstructor()
            ->getMock();

        $this->connection = $this->getMockBuilder(
            'Ratchet\ConnectionInterface'
        )->getMock();

        $this->server = new Server($logger);
    }

    /**
     * @return void
     *
     * @covers FlorianWolters\Application\Chat\Server::onOpen
     */
    public function testOnOpen()
    {
        $this->server->onOpen($this->connection);
        $this->assertEquals(0, $this->server->countClients());
    }

    /**
     * @return void
     *
     * @covers FlorianWolters\Application\Chat\Server::onMessage
     */
    public function testOnMessageFirst()
    {
        $this->server->onMessage($this->connection, 'Florian Wolters');
        $this->assertEquals(1, $this->server->countClients());

        return array($this->server, $this->connection);
    }

    /**
     * @return void
     *
     * @covers FlorianWolters\Application\Chat\Server::onMessage
     * @depends testOnMessageFirst
     */
    public function testOnMessageSecond(array $args)
    {
        $server = $args[0];
        $connection = $args[1];

        $server->onMessage($connection, 'hello, world');
        $this->assertEquals(1, $server->countClients());
    }

    /**
     * @return void
     *
     * @covers FlorianWolters\Application\Chat\Server::onClose
     * @depends testOnMessageFirst
     */
    public function testOnClose(array $args)
    {
        $server = $args[0];
        $connection = $args[1];

        $server->onClose($connection);
        $this->assertEquals(0, $server->countClients());
    }

    /**
     * @return void
     *
     * @covers FlorianWolters\Application\Chat\Server::onError
     */
    public function testOnError()
    {
        $this->server->onError($this->connection, new \Exception);
    }

}
