<?php
/**
 * `Server.php`
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

use Monolog\Logger;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * The {@link Server} class contains the logic for the chat server.
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
class Server implements MessageComponentInterface
{

    /**
     * The {@link Logger} to use.
     *
     * @var Logger
     */
    private $logger;

    /**
     * The clients connected to this {@link Server}.
     *
     * @var array
     */
    private $clients = array();

    /**
     * Constructs a new {@link Server} with the specified {@link Logger}.
     *
     * @param Logger $logger The {@link Logger} to use.
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        $this->clients = new \SplObjectStorage;
    }

    /**
     * Triggered when a new client connection is opened.
     *
     * @param ConnectionInterface $conn The connection that opened.
     *
     * @return void
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->logger->addInfo('A client has connected.');
    }

    /**
     * Triggered when a client sends data.
     *
     * @param ConnectionInterface $connection The connection that sent the
     *                                        message.
     * @param string              $data       The data.
     *
     * @return void
     */
    public function onMessage(ConnectionInterface $connection, $data)
    {
        if ($this->clients->contains($connection)) {
            // The client has already authenticated.

            $username = $this->clients[$connection];
            $message = new Model\Message($data, $username);

            $this->logger->addInfo(
                'The client has sent a message.',
                array(
                    'datetime' => $message->getDatetime(),
                    'username' => $message->getUsername(),
                    'message' => $message->getText()
                )
            );

            foreach ($this->clients as $client) {
                $client->send($message->__toString());
            }
        } else {
            // The client has not authenticated yet.
            // The first message contains the username.

            // Store the client to send messages to later.
            $this->clients[$connection] = $data;

            $this->logger->addInfo(
                'The client has authenticated.', array('username' => $data)
            );
        }
    }

    /**
     * Triggered before or after a socket is closed (depends on how it's
     * closed).
     *
     * @param ConnectionInterface $connection The connection that is
     *                                        closing/closed.
     *
     * @return void
     */
    public function onClose(ConnectionInterface $connection)
    {
        // The connection is closed, remove it, as we can no longer send it
        // messages
        $this->clients->detach($connection);

        $this->logger->addInfo('A client has disconnected.');
    }

    /**
     * If there is an error with one of the connections, or somewhere in the
     * application where an {@link Exception} is thrown, the {@link Exception}
     * is sent back down the stack, handled by the server and bubbled back up
     * the application through this method.
     *
     * @param ConnectionInterface $connection The connection that raised the
     *                                        error.
     * @param Exception           $ex         The {@link Exception}.
     *
     * @return void
     */
    public function onError(ConnectionInterface $connection, \Exception $ex)
    {
        $connection->close();

        $this->logger->addError("An error has occurred: {$ex->getMessage()}");
    }

}
