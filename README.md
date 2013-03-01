# FlorianWolters\Application\Chat\Server\Server

[![Build Status](https://secure.travis-ci.org/FlorianWolters/PHP-Application-Chat-Server.png?branch=master)](http://travis-ci.org/FlorianWolters/PHP-Application-Chat-Server)

**FlorianWolters\Application\Chat\Server** is a [PHP][17] command line interface application implementing a chat server that uses [The WebSocket Protocol][21].

Access to the shell/command-line is required and a dedicated machine with root access is recommended to run **FlorianWolters\Application\Chat\Server**.

See [FlorianWolters/WebSocket-Chat-Client][22] for a multi-user chat client that is compatible with this chat server.

## Features

* Artifacts tested with both static and dynamic test procedures:
    * Dynamic component tests (unit tests) implemented using [PHPUnit][19].
    * Static code analysis performed using the following tools:
        * [PHP_CodeSniffer][14]: Style Checker
        * [PHP Mess Detector (PHPMD)][18]: Code Analyzer
        * [phpcpd][4]: Copy/Paste Detector (CPD)
        * [phpdcd][5]: Dead Code Detector (DCD)
* Provides a complete Application Programming Interface (API) documentation generated with the documentation generator [ApiGen][2].

  Click [here][1] for the current API documentation.
* Follows the [PSR-0][6] requirements for autoloader interoperability.
* Follows the [PSR-1][7] basic coding style guide.
* Follows the [PSR-2][8] coding style guide.
* Follows the [Semantic Versioning][20] Specification (SemVer) 2.0.0-rc.1.

## Requirements

* [PHP][17] >= 5.3.3
* [Monolog][23] >= 1.4
* [Ratchet][24] >= 0.2
* [Symfony Console Component][25] >= 2.1

## Usage

The binary scripts are placed in the folder `src/bin`:

* `chat-server.php` for *-nix operating systems
* `chat-server.cmd` for Windows operating systems (simply calls `chat-server.php`)

Run the following for the general help of the application:

* *-nix shell

  ```sh
  ./chat-server.php help
  ```

* Windows command-line

  ```cmd
  chat-server.cmd help
  ```

At this moment, the server only supports the `run` command. To get help for the `run` command, run the following:

* *-nix shell

  ```sh
  ./chat-server.php help run
  ```

* Windows command-line

  ```cmd
  chat-server.cmd help run
  ```

The following output should be displayed.

    Usage:
     run [--logtype="..."] [--loglevel="..."] [--test] [port] [address]

    Arguments:
     port        The TCP/IP port to use. (default: 8000)
     address     The IP address to use. (default: 0.0.0.0)

    Options:
     --logtype   The type of logger to use (STDOUT, FILE). (multiple values allowed)
     --loglevel  The level for the logger to use (DEBUG, INFO, WARNING, ERROR, CRITICAL, ALERT}). (default: WARNING)
     --test      Run the application in test mode (for automated tests).

    Help:
     Runs the chat server on the optionally specified TCP/IP port and the optionally specified IP address.

     The type of the logger (log to STDOUT, log to the file "chat-server.log" or log to both) and the level of the logger can be specified. The default level logs warnings and all levels above.

## Installation

Clone the repository into a new directory.

    git clone git://github.com/FlorianWolters/PHP-Application-Chat-Server.git

**FlorianWolters\Application\Chat\Server** should be installed using the dependency manager [Composer][3]. [Composer][3] can be installed with [PHP][6].

    php -r "eval('?>'.file_get_contents('http://getcomposer.org/installer'));"

> This will just check a few [PHP][17] settings and then download `composer.phar` to your working directory. This file is the [Composer][3] binary. It is a PHAR ([PHP][17] archive), which is an archive format for [PHP][17] which can be run on the command line, amongst other things.
>
> Next, run the `install` command to resolve and download dependencies:

    php composer.phar install

## License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License along with this program. If not, see <http://gnu.org/licenses/lgpl.txt>.

[1]: http://blog.florianwolters.de/PHP-Application-Chat-Server
     "FlorianWolters\Component\Core | Application Programming Interface (API) documentation"
[2]: http://apigen.org
     "ApiGen | API documentation generator for PHP 5.3.+"
[3]: http://getcomposer.org
     "Composer"
[4]: https://github.com/sebastianbergmann/phpcpd
     "sebastianbergmann/phpcpd · GitHub"
[5]: https://github.com/sebastianbergmann/phpdcd
     "sebastianbergmann/phpdcd · GitHub"
[6]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
     "PSR-0 requirements for autoloader interoperability"
[7]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
     "PSR-1 basic coding style guide"
[8]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
     "PSR-2 coding style guide"
[14]: http://pear.php.net/package/PHP_CodeSniffer
      "PHP_CodeSniffer"
[17]: http://php.net
      "PHP: Hypertext Preprocessor"
[18]: http://phpmd.org
      "PHPMD - PHP Mess Detector"
[19]: http://phpunit.de
      "sebastianbergmann/phpunit · GitHub"
[20]: http://semver.org
      "Semantic Versioning"
[21]: http://tools.ietf.org/html/rfc6455
      "RFC 6455 - The WebSocket Protocol"
[22]: https://github.com/FlorianWolters/WebSocket-Chat-Client
      "FlorianWolters/WebSocket-Chat-Client · GitHub"
[23]: https://github.com/Seldaek/monolog
      "Seldaek/monolog · GitHub"
[24]: http://socketo.me
      "Ratchet - PHP WebSockets"
[25]: http://symfony.com/doc/current/components/console.html
      "The Console Component (current) - Symfony"

