<?php
/**
 * `ServerApplication.php`
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

use Symfony\Component\Console\Application;

/**
 * The {@link ServerApplication} class starts the chat server application.
 *
 * @category  Application
 * @package   Chat
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2012 Florian Wolters
 * @license   http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @version   Release: @package_version@
 * @link      http://github.com/FlorianWolters/PHP-WebSocket-Chat-Server
 * @since     Class available since Release 0.1.0
 */
class ServerApplication extends Application
{

    /**
     * Constructs a new chat server application.
     */
    public function __construct()
    {
        parent::__construct('FlorianWolters\Application\Chat', '0.1.0');
        $this->setAutoExit(false);
        $this->add(
            new Command\RunServerCommand
        );
    }

}
