<?php
namespace FlorianWolters\Application\Chat;

/**
 * Test class for {@see Server}.
 *
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2012-2013 Florian Wolters
 * @license   http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @link      http://github.com/FlorianWolters/PHP-Application-Chat-Server
 * @since     Class available since Release 0.1.0
 * @todo      Add more complex test cases.
 *
 * @covers    FlorianWolters\Application\Chat\Server
 */
class ServerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The {@see Server} object under test.
     *
     * @var Server
     */
    private $server;

    /**
     * The mocked {@see ConnectionInterface}.
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

        $this->connection = new \Ratchet\Tests\Mock\Connection;
        $this->connection->resourceId = '#1';
        $this->server = new Server($logger);
    }

    /**
     * @return void
     *
     * @coversDefaultClass onOpen
     */
    public function testOnOpen()
    {
        $this->server->onOpen($this->connection);
        $this->assertEquals(0, $this->server->countClients());
    }

    /**
     * @return void
     *
     * @coversDefaultClass onMessage
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
     * @coversDefaultClass onMessage
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
     * @coversDefaultClass onClose
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
     * @coversDefaultClass onError
     */
    public function testOnError()
    {
        $this->server->onError($this->connection, new \Exception);
    }
}
