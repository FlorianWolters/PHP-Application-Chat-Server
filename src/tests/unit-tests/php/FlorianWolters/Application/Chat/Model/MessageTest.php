<?php
namespace FlorianWolters\Application\Chat\Model;

/**
 * Test class for {@see Message}.
 *
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2012-2013 Florian Wolters
 * @license   http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @link      http://github.com/FlorianWolters/PHP-Application-Chat-Server
 * @since     Class available since Release 0.1.0
 *
 * @covers    FlorianWolters\Application\Chat\Model\Message
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The {@see Message} object under test.
     *
     * @var Message
     */
    private $message;

    /**
     * Sets up the fixture.
     *
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->message = new Message(
            'hello, world',
            'Florian Wolters',
            new \DateTime('2013-01-27 00:00:00')
        );
    }

    /**
     * @return void
     *
     * @coversDefaultClass __toString
     */
    public function testMagicToString()
    {
        $expected = '{"ts":"2013-01-27 00:00:00","uid":"Florian Wolters","msg":"hello, world"}';
        $actual = $this->message->__toString();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     *
     * @coversDefaultClass getUsername
     */
    public function testGetUsername()
    {
        $expected = 'Florian Wolters';
        $actual = $this->message->getUsername();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     *
     * @coversDefaultClass getDatetime
     */
    public function testGetDatetime()
    {
        $expected = '2013-01-27 00:00:00';
        $actual = $this->message->getDatetime();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     *
     * @coversDefaultClass getText
     */
    public function testGetText()
    {
        $expected = 'hello, world';
        $actual = $this->message->getText();
        $this->assertEquals($expected, $actual);
    }
}
