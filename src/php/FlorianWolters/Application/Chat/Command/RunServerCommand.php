<?php
/**
 * `RunServerCommand.php`
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
 * @subpackage Command
 * @author     Florian Wolters <wolters.fl@gmail.com>
 * @copyright  2012 Florian Wolters
 * @license    http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @version    GIT: $Id$
 * @link       http://github.com/FlorianWolters/PHP-WebSocket-Chat-Server
 * @since      File available since Release 0.1.0
 */

namespace FlorianWolters\Application\Chat\Command;

use FlorianWolters\Application\Chat\Server;
use Monolog\Logger;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\Socket\ConnectionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The {@link RunServerCommand} starts the chat server.
 *
 * @category   Application
 * @package    Chat
 * @subpackage Command
 * @author     Florian Wolters <wolters.fl@gmail.com>
 * @copyright  2012 Florian Wolters
 * @license    http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @version    Release: @package_version@
 * @link       http://github.com/FlorianWolters/PHP-WebSocket-Chat-Server
 * @since      Class available since Release 0.1.0
 */
class RunServerCommand extends Command
{

    /**
     * The default TCP/IP port to use for the chat server.
     *
     * @var integer
     */
    const DEFAULT_PORT = 8000;

    /**
     * The default IP address to use for the chat server.
     *
     * @var string
     */
    const DEFAULT_ADDRESS = '0.0.0.0';

    /**
     * The {@link Logger} to use.
     *
     * @var Logger
     */
    private $logger;

    /**
     * Constructs a new {@link RunServerCommand} with the specified {@link
     * Logger}.
     *
     * @param Logger $logger The {@link Logger} to use.
     */
    public function __construct(Logger $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

    /**
     * Configure this command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('run')
            ->setDescription('Runs the chat server.')
            ->addArgument(
                'port',
                InputArgument::OPTIONAL,
                'The TCP/IP port to use.',
                self::DEFAULT_PORT
            )
            ->addArgument(
                'address',
                InputArgument::OPTIONAL,
                'The IP address to use.',
                self::DEFAULT_ADDRESS
            )
            ->setHelp(
                'Runs the chat server on the optionally specified TCP/IP port '
                . '(default: ' . self::DEFAULT_PORT . ') and the optionally'
                . ' specified IP address (default: ' . self::DEFAULT_ADDRESS
                . ').'
            );
    }

    /**
     * Execute this command.
     *
     * @param InputInterface  $input  An {@link InputInterface} instance.
     * @param OutputInterface $output An {@link OutputInterface} instance.
     *
     * @return integer `0` on success; `1` if unable to start the chat server.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $return = 0;

        $port = $input->getArgument('port');
        $address = $input->getArgument('address');

        try {
            $webSocketServer = new WsServer(
                new Server($this->logger)
            );

            // TODO Remove @ if fixed in React/Socket/Server.
            // Link: https://github.com/react-php/react/issues/45
            $server = @IoServer::factory($webSocketServer, $port, $address);

            $output->writeln('Starting chat server...');
            $output->writeln('');
            $output->writeln(
                "Waiting for incoming connections on {$address}:{$port}..."
            );

            $this->logger->addDebug(
                'Starting chat server...',
                array('port' => $port, 'address' => $address)
            );

            $server->run();
        } catch (ConnectionException $ex) {
            $message = 'Unable to start the chat server';
            $output->writeln(
                "{$message} on {$address}:{$port}."
            );

            $this->logger->addWarning(
                "{$message}.",
                array('port' => $port, 'address' => $address)
            );

            $return = 1;
        }

        return $return;
    }

}
