<?php
namespace FlorianWolters\Application\Chat;

use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * Test class for {@link ServerApplication}.
 *
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2012-2013 Florian Wolters
 * @license   http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @link      http://github.com/FlorianWolters/PHP-Application-Chat-Server
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
        $display = $this->applicationTester->getDisplay();

        $expected = 'Starting chat server...';
        $this->assertContains($expected, $display);

        $expected = 'Waiting for incoming connections on 0.0.0.0:8000...';
        $this->assertContains($expected, $display);
    }
}
