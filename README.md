# FlorianWolters\Application\Chat

[![Build Status](https://secure.travis-ci.org/FlorianWolters/PHP-WebSocket-Chat-Server.png?branch=master)](http://travis-ci.org/FlorianWolters/PHP-WebSocket-Chat-Server)

**FlorianWolters\Application\Chat** is a simple [PHP][17] application implementing a chat server that uses [The WebSocket Protocol][21].

See [FlorianWolters/WebSocket-Chat-Client][22] for a multi-user chat client that is compatible with this chat server.

## Requirements

* [PHP][17] 5.3.0 (or later)
* [Monolog][23] 1.8.0 (or later)
* [Ratchet][24] 0.1.4 (or later)
* [Symfony\Component\Console][25] 2.0.0 (or later)

## Features

* Artifacts tested with both static and dynamic test procedures:
  * Component tests (unit tests) implemented with [PHPUnit][19].
  * Static code analysis with the style checker [PHP_CodeSniffer][14] and the source code analyzer [PHP Mess Detector (PHPMD)][18], [phpcpd][4] and [phpdcd][5].
* Provides support for the dependency manager [Composer][3].
* Provides a complete Application Programming Interface (API) documentation generated with the documentation generator [ApiGen][2]. Click [here][1] for the online API documentation.
* Follows the [PSR-0][6] requirements for autoloader interoperability.
* Follows the [PSR-1][7] basic coding style guide.
* Follows the [PSR-2][8] coding style guide.
* Follows the [Semantic Versioning][20] requirements for versioning (`<Major version>.<Minor version>.<Patch level>`).

## Installation

**FlorianWolters\Application\Chat** should be installed using the dependency manager [Composer][3]. [Composer][1] can be installed with [PHP][6].

    php -r "eval('?>'.file_get_contents('http://getcomposer.org/installer'));"

> This will just check a few [PHP][17] settings and then download `composer.phar` to your working directory. This file is the [Composer][1] binary. It is a PHAR ([PHP][17] archive), which is an archive format for [PHP][17] which can be run on the command line, amongst other things.
>
> Next, run the `install` command to resolve and download dependencies:

    php composer.phar install

## Usage

The binary scripts are places in the folder `src/bin`:

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

The following output should be displayed. That is all one needs to know to start the chat server.

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

## License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License along with this program. If not, see http://gnu.org/licenses/lgpl.txt.

[1]: http://blog.florianwolters.de/PHP-WebSocket-Chat-Server
[2]: http://apigen.org
[3]: http://getcomposer.org
[4]: https://github.com/sebastianbergmann/phpcpd
[5]: https://github.com/sebastianbergmann/phpdcd
[6]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[7]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[8]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[14]: http://pear.php.net/package/PHP_CodeSniffer
[17]: http://php.net
[18]: http://phpmd.org
[19]: http://phpunit.de
[20]: http://semver.org
[21]: http://tools.ietf.org/html/rfc6455
[22]: https://github.com/FlorianWolters/WebSocket-Chat-Client
[23]: https://github.com/Seldaek/monolog
[24]: http://socketo.me
[25]: http://symfony.com/doc/current/components/console.html
