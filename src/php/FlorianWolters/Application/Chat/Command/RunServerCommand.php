<?php
namespace FlorianWolters\Application\Chat\Command;

use FlorianWolters\Application\Chat\Server;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\Socket\ConnectionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The class {@see RunServerCommand} starts the chat server.
 *
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2012-2013 Florian Wolters
 * @license   http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @link      http://github.com/FlorianWolters/PHP-Application-Chat-Server
 * @since     Class available since Release 0.1.0
 * @todo      Refactoring of this class (high CCN).
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
     * The default level to use for the {@see Logger}.
     *
     * @var string
     */
    const DEFAULT_LOGLEVEL = Logger::WARNING;

    /**
     * The available levels for the {@see Logger}.
     *
     * @var mixed[]
     * @todo Bad design, since Monolog also declares these. But the Monolog API
     *       does not allow to access the data.
     */
    private static $logLevels = array(
        100 => 'DEBUG',
        200 => 'INFO',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT'
    );

    /**
     * The {@see Logger} to use.
     *
     * @var Logger
     */
    private $logger;

    /**
     * Constructs a new {@see RunServerCommand}.
     */
    public function __construct()
    {
        $name = 'run';
        parent::__construct($name);
        $this->logger = new Logger($name);
    }

    /**
     * Configures this {@see RunServerCommand}.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setDescription('Runs the chat server.')
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
            ->addOption(
                'logtype',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'The type of logger to use (STDOUT, FILE).'
            )
            ->addOption(
                'loglevel',
                null,
                InputOption::VALUE_REQUIRED,
                'The level for the logger to use ('
                . \implode(self::$logLevels, ', ') . '}).',
                self::$logLevels[self::DEFAULT_LOGLEVEL]
            )
            ->addOption(
                'test',
                null,
                null,
                'Run the application in test mode (for automated tests).'
            )
            ->setHelp(
                'Runs the chat server on the optionally specified TCP/IP port '
                . 'and the optionally specified IP address.'
                . \PHP_EOL . \PHP_EOL
                . 'The type of the logger (log to STDOUT, log to the file '
                . '"chat-server.log" or log to both) and the level of the '
                . 'logger can be specified. The default level logs warnings '
                . 'and all levels above.'
            );
    }


    /**
     * Executes this {@see RunServerCommand}.
     *
     * @param InputInterface  $input  An {@see InputInterface} instance.
     * @param OutputInterface $output An {@see OutputInterface} instance.
     *
     * @return integer `0` on success; `1` if unable to start the chat server;
     *                 `2` if the `--logtype` option is invalid; `3` if the
     *                 `--loglevel` option is invalid; `4` if the `address`
     *                 argument is invalid; `5` if the `port` argument is
     *                 invalid.
     * @todo Refactoring of this method (high CCN).
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getArgument('port');
        if (false === $this->isPort($port)) {
            $this->writeInvalidUsageMessage(
                $output,
                'The "port" argument value is invalid.'
            );
            return 5;
        }

        $address = $input->getArgument('address');
        if (false === $this->isIpAddress($address)) {
            $this->writeInvalidUsageMessage(
                $output,
                'The "address" argument value is invalid.'
            );
            return 4;
        }

        $logLevelName = $input->getOption('loglevel');
        $logLevel = \array_search($logLevelName, self::$logLevels, true);
        if (false === $logLevel) {
            $this->writeInvalidUsageMessage(
                $output,
                'The specified --loglevel option value is invalid.'
            );
            return 3;
        }

        $logType = $input->getOption('logtype');
        if (false === $this->createHandlers($logType, $logLevel)) {
            $this->writeInvalidUsageMessage(
                $output,
                'The specified --logtype option value is invalid.'
            );
            return 2;
        }

        try {
            $server = new Server($this->logger);
            $webSocketServer = new WsServer($server);

            if (false === $input->getOption('test')) {
                $inputOutputServer = IoServer::factory(
                    $webSocketServer,
                    $port,
                    $address
                );
            }

            $this->logger->addDebug(
                'The chat server was started.',
                array('port' => $port, 'address' => $address)
            );

            $this->writeSuccessMessage($output, $port, $address);

            if (false === $input->getOption('test')) {
                // Only run if not in test mode.
                $inputOutputServer->run();
            }
        } catch (ConnectionException $ex) {
            $this->logger->addError(
                'The chat server could not be started',
                array(
                    'port' => $port,
                    'address' => $address,
                    'exception' => $ex->getMessage()
                )
            );

            $this->writeFailureMessage($output, $port, $address);

            return 1;
        }

        return 0;
    }

    // TODO Use a external validation component or create a helper class with
    // validation functions.

    /**
     * Checks whether the specified argument is a valid TCP/IP port.
     *
     * @param mixed $value The argument to validate.
     *
     * @return boolean `true` on success; `false` on failure.
     */
    private function isPort($value)
    {
        return (true === \is_numeric($value))
            && ($value > -1)
            && ($value < 65536);
    }

    /**
     * Checks whether the specified argument is a valid IP address.
     *
     * @param mixed $value The argument to validate.
     *
     * @return boolean `true` on success; `false` on failure.
     */
    private function isIpAddress($value)
    {
        return \filter_var($value, \FILTER_VALIDATE_IP);
    }

    /**
     * Writes a message to the output saying that this {@see RunServerCommand}
     * has been successful.
     *
     * @param OutputInterface $output  An {@see OutputInterface} instance.
     * @param integer         $port    The TCP/IP port number.
     * @param string          $address The IP address.
     *
     * @return void
     */
    private function writeSuccessMessage(
        OutputInterface $output,
        $port,
        $address
    ) {
        $output->writeln('Starting chat server...');
        $output->writeln('');
        $output->writeln(
            "Waiting for incoming connections on {$address}:{$port}..."
        );
    }

    /**
     * Writes a message to the output saying that this {@see RunServerCommand}
     * has been failed.
     *
     * @param OutputInterface $output  An {@see OutputInterface} instance.
     * @param integer         $port    The TCP/IP port number.
     * @param string          $address The IP address.
     *
     * @return void
     */
    private function writeFailureMessage(
        OutputInterface $output,
        $port,
        $address
    ) {
        $output->writeln(
            "Unable to start the chat server on {$address}:{$port}."
        );
    }

    /**
     * Writes a message to the output saying that this {@see RunServerCommand}
     * has been used incorrectly.
     *
     * @param OutputInterface $output An {@see OutputInterface} instance.
     * @param string          $reason The reason for the invalid usage.
     *
     * @return void
     */
    private function writeInvalidUsageMessage(OutputInterface $output, $reason)
    {
        $output->writeln($reason);
        $output->writeln('');
        // TODO Retrieve the name of the script dynamically.
        $output->writeln(
            'Run "chat-server help ' . $this->getName() . '" for the help.'
        );
    }

    /**
     * Creates the handlers for the {@see Logger}.
     *
     * @param string[] $logType  The logtype(s).
     * @param integer  $logLevel The loglevel.
     *
     * @return boolean `true` if the handlers have been created; `false` if one
     *                 or more invalid handlers have been specified.
     */
    private function createHandlers(array $logType, $logLevel)
    {
        $result = true;
        $handlers = array();

        if (true === empty($logType)) {
            $handlers[] = new NullHandler;
        } else {
            foreach ($logType as $each) {
                switch (\strtolower($each)) {
                    case 'stdout':
                        $handlers[] = new StreamHandler(\STDOUT, $logLevel);
                        break;
                    case 'file':
                        // TODO Make filepath configurable.
                        $handlers[] = new StreamHandler(
                            __DIR__ . '/../../../../../bin/chat-server.log',
                            $logLevel
                        );
                        break;
                    default:
                        $result = false;
                        break 2;
                }
            }
        }

        if (true === $result) {
            $this->pushHandlersToLogger($handlers);
        }

        return $result;
    }

    /**
     * Pushes the specified handlers to the {@see Logger}.
     *
     * @param HandlerInterface[] $handlers The handlers to push.
     *
     * @return void
     */
    private function pushHandlersToLogger(array $handlers)
    {
        foreach ($handlers as $handler) {
            $this->logger->pushHandler($handler);
        }
    }
}
