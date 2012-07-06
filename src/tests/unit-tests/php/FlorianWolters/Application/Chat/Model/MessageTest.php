<?php
/**
 * `MessageTest.php`
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
 * @subpackage Model
 * @author     Florian Wolters <wolters.fl@gmail.com>
 * @copyright  2012 Florian Wolters
 * @license    http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @version    GIT: $Id$
 * @link       http://github.com/FlorianWolters/PHP-WebSocket-Chat-Server
 * @since      File available since Release 0.1.0
 */

namespace FlorianWolters\Application\Chat\Model;

/**
 * Test class for {@link Message}.
 *
 * @category   Application
 * @package    Chat
 * @subpackage Model
 * @author     Florian Wolters <wolters.fl@gmail.com>
 * @copyright  2012 Florian Wolters
 * @license    http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @version    Release: @package_version@
 * @link       http://github.com/FlorianWolters/PHP-WebSocket-Chat-Server
 * @link       Message
 * @since      Class available since Release 0.1.0
 *
 * @covers     FlorianWolters\Application\Chat\Model\Message
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The object under test.
     *
     * @var Message
     */
    private $object;

    /**
     * Sets up the fixture.
     *
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->object = new Message(
            'hello, world', 'Florian Wolters',
            new \DateTime('2012-07-05 00:00:00')
        );
    }

    /**
     * @return void
     *
     * @covers FlorianWolters\Application\Chat\Model\Message::__toString
     */
    public function testMagicToString()
    {
        $expected = '{"ts":"2012-07-05 00:00:00","uid":"Florian Wolters","msg":"hello, world"}';
        $actual = $this->object->__toString();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     *
     * @covers FlorianWolters\Application\Chat\Model\Message::getUsername
     */
    public function testGetUsername()
    {
        $expected = 'Florian Wolters';
        $actual = $this->object->getUsername();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     *
     * @covers FlorianWolters\Application\Chat\Model\Message::getDatetime
     */
    public function testGetDatetime()
    {
        $expected = '2012-07-05 00:00:00';
        $actual = $this->object->getDatetime();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     *
     * @covers FlorianWolters\Application\Chat\Model\Message::getText
     */
    public function testGetText()
    {
        $expected = 'hello, world';
        $actual = $this->object->getText();
        $this->assertEquals($expected, $actual);
    }

}
