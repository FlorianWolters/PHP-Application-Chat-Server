<?php
namespace FlorianWolters\Application\Chat\Model;

/**
 * An object of class {@link Message} wraps a chat message into an object.
 *
 * @author    Florian Wolters <wolters.fl@gmail.com>
 * @copyright 2012-2013 Florian Wolters
 * @license   http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @link      http://github.com/FlorianWolters/PHP-Application-Chat-Server
 * @since     Class available since Release 0.1.0
 */
class Message
{
    /**
     * The format for the date and time of a {@link Message}.
     *
     * @var string
     */
    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * The text of this {@link Message}.
     *
     * @var string
     */
    private $text;

    /**
     * The username of the user who wrote this{@link Message}.
     *
     * @var string
     */
    private $username;

    /**
     * The date and time of this {@link Message}.
     *
     * @var integer
     */
    private $dateTime;

    /**
     * Constructs a new {@link Message} with a specified text, a specified
     * username and an optional date and time.
     *
     * If the datetime is not specified, the current datetime is used.
     *
     * @param string    $text     The text of the {@link Message}.
     * @param string    $username The username of the user who wrote this {@link
     *                            Message}.
     * @param \DateTime $dateTime The date and time of the {@link Message}.
     */
    public function __construct($text, $username, \DateTime $dateTime = null)
    {
        if (null === $dateTime) {
            $dateTime = new \DateTime;
        }

        $this->text = \htmlspecialchars($text, \ENT_QUOTES);
        $this->username = \htmlspecialchars($username, \ENT_QUOTES);
        $this->dateTime = $dateTime->format(self::DATETIME_FORMAT);
    }

    /**
     * Returns a string representation of this {@link Message}.
     *
     * @return string The string representation.
     */
    public function __toString()
    {
        return \json_encode(
            array(
                'ts' => $this->dateTime,
                'uid' => $this->username,
                'msg' => $this->text
            )
        );
    }

    /**
     * Returns the text of this {@link Message}.
     *
     * @return string The text.
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Returns the username of the user who wrote this {@link Message}.
     *
     * @return string The username.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns the date and time of this {@link Message}.
     *
     * @return string The date and time.
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }
}
