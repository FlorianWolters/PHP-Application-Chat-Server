<?php
namespace FlorianWolters\Application\Chat;

use Monolog\Logger;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * The {@link Server} class contains the logic for the chat server.
 *
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2012-2013 Florian Wolters
 * @link      http://github.com/FlorianWolters/PHP-Application-Chat-Server
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
     * @var SplObjectStorage
     */
    private $clients;

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
     * @param ConnectionInterface $connection The connection that opened.
     *
     * @return void
     */
    public function onOpen(ConnectionInterface $connection)
    {
        $this->logger->addInfo(
            "A client has connected.",
            array(
                'remoteAddress' => $connection->remoteAddress,
                'resourceId' => $connection->resourceId
            )
        );
    }

    /**
     * Triggered when a connection sends a message.
     *
     * @param ConnectionInterface $connection The connection that sent the
     *                                        message.
     * @param string              $data       The message.
     *
     * @return void
     */
    public function onMessage(ConnectionInterface $connection, $data)
    {
        // TODO Refactor into methods (reason: high CCN).

        if ($this->clients->contains($connection)) {
            // The client has already authenticated.
            // Send the message from the client to each connected client.
            $this->sendMessageToClients($connection, $data);
        } else {
            // The client has not authenticated yet.
            // The first message contains the username.

            // Check if the username is already in use.
            if ($this->isUsernameAvailable($data)) {
                $this->logger->addDebug(
                    'The username is available.',
                    array('username' => $data)
                );

                // Store the client to send messages to later.
                $this->addClient($connection, $data);
            } else {
                $this->logger->addDebug(
                    'The username is in use.',
                    array('username' => $data)
                );

                // Send a message to the client saying that the client is trying
                // to use an unavailable username.
                $this->sendMessageUsernameInUse($connection);
                // Close the connection.
                $connection->close();
            }
        }
    }

    /**
     * Checks whether the specified username is available (not in use).
     *
     * @param string $username The username to check.
     *
     * @return boolean `true` if the username is available; `false` otherwise.
     */
    private function isUsernameAvailable($username)
    {
        $result = true;

        foreach ($this->clients as $client) {
            if ($this->clients->offsetGet($client) === $username) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    /**
     * Sends a message to the specified connection, saying that the connection
     * is trying to use an username that is already in use.
     *
     * @param ConnectionInterface $connection The connection to sent the
     *                                        message to.
     *
     * @return void
     */
    private function sendMessageUsernameInUse(
        ConnectionInterface $connection
    ) {
        $messageObj = new Model\Message(
            'The username is already in use.',
            'chat-server'
        );
        $connection->send($messageObj->__toString());
    }

    /**
     * Sends the specified message from the specified connection to each
     * connected client.
     *
     * @param ConnectionInterface $connection The connection that sent the
     *                                        message.
     * @param string              $message    The message.
     *
     * @return void
     */
    private function sendMessageToClients(
        ConnectionInterface $connection,
        $message
    ) {
        $username = $this->clients[$connection];
        $messageObj = new Model\Message($message, $username);

        $this->logger->addInfo(
            'A client has sent a message.',
            array(
                'remoteAddress' => $connection->remoteAddress,
                'resourceId' => $connection->resourceId,
                'datetime' => $messageObj->getDateTime(),
                'username' => $messageObj->getUsername(),
                'message' => $messageObj->getText()
            )
        );

        foreach ($this->clients as $client) {
            $client->send($messageObj->__toString());
        }
    }

    /**
     * Adds the specified connection to the connected clients.
     *
     * @param ConnectionInterface $connection The connection to add.
     * @param string              $username   The username of the client.
     *
     * @return void
     */
    private function addClient(
        ConnectionInterface $connection,
        $username
    ) {
        $this->clients->attach($connection, $username);

        $this->logger->addInfo(
            'A client has authenticated.',
            array('username' => $username)
        );
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

        $this->logger->addInfo(
            "A client has disconnected.",
            array(
                'remoteAddress' => $connection->remoteAddress,
                'resourceId' => $connection->resourceId
            )
        );
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

    /**
     * Returns the number of clients connected to this {@link Server}.
     *
     * @return integer The number of clients.
     */
    public function countClients()
    {
        return $this->clients->count();
    }
}
