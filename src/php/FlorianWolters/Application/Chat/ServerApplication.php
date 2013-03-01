<?php
namespace FlorianWolters\Application\Chat;

use Symfony\Component\Console\Application;

/**
 * The class {@see ServerApplication} starts the chat server application.
 *
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2012-2013 Florian Wolters
 * @license   http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @link      http://github.com/FlorianWolters/PHP-Application-Chat-Server
 * @since     Class available since Release 0.1.0
 */
class ServerApplication extends Application
{
    /**
     * Constructs a new {@see ServerApplication}.
     */
    public function __construct()
    {
        parent::__construct(__NAMESPACE__, '0.1.1');
        $this->setAutoExit(false);
        $this->add(
            new Command\RunServerCommand
        );
    }
}
