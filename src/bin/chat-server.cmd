@ECHO OFF
REM chat-server.cmd
REM
REM This program is free software: you can redistribute it and/or modify it
REM under the terms of the GNU Lesser General Public License as published by the
REM Free Software Foundation, either version 3 of the License, or (at your
REM option) any later version.
REM
REM This program is distributed in the hope that it will be useful, but WITHOUT
REM ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
REM FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public License
REM for more details.
REM
REM You should have received a copy of the GNU Lesser General Public License
REM along with this program.  If not, see <http://gnu.org/licenses/lgpl.txt>.
REM
REM Author:    Florian Wolters <wolters.fl@gmail.com>
REM Copyright: 2012-2013 Florian Wolters
REM License:   http://gnu.org/licenses/lgpl.txt LGPL-3.0+
REM Link:      http://github.com/FlorianWolters/PHP-Application-Chat-Server

TITLE FlorianWolters\Application\Chat\Server
php chat-server.php %*
