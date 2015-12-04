<?php
namespace IpType\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type;
use PDO;

/**
 *
 * An Ip Type for the Database Framework of CakePHP, which converts raw ip addresses represented as human readable strings (127.0.0.1, ::1) to byte strings with inet_pton to store them into a database.
 * The marshal function does nothing as it is usual that raw ip address strings are given in data, so nothing has to be converted in this state.
 *
 * The final converted value for the database is meant to be stored as a LOB value. I used a VARBINARY with 16 bytes. It is long enough for also IPv6 addresses.
 *
 * The class was written for an application for InnoGames GmbH ( http://www.innogames.com ). The permission was given to make this class public.
 *
 * @author TheFRedFox <thefredfox@googlemail.com>
 * @access public
 * @see    \Cake\Database\Type
 */
class IpType extends Type
{

    /**
     * {@inheritdoc}
     *
     * It converts from ip address in byte string to a human readable string with inet_ntop.
     *
     * @return null|mixed null, if value is null, else the human readable string of the byte ip string in value
     *
     * @see inet_ntop
     */
    public function toPHP($value, Driver $driver)
    {
        if ($value === null)
        {
            return null;
        }

        return inet_ntop($value);
    }

    /**
     * {@inheritdoc}
     *
     * It does nothing, as it is usual the ip address is given in raw format in data to be marshaled. So it just returns the value back.
     *
     * @return mixed just the value back which was given as parameter
     */
    public function marshal($value)
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     *
     * It converts from ip address in human readable string to a byte string representation with inet_pton.
     *
     * @return null|string null, if value is null, else the byte string of the human readable ip string in value
     *
     * @see inet_pton
     */
    public function toDatabase($value, Driver $driver)
    {
        if ($value === null)
        {
            return null;
        }

        return inet_pton($value);
    }

    /**
     * {@inheritdoc}
     *
     * @return int PDO::PARAM_NULL if value is null, else PDO::PARAM_LOB
     *
     * @see PDO::PARAM_NULL
     * @see PDO::PARAM_LOB
     */
    public function toStatement($value, Driver $driver)
    {
        if ($value === null)
        {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_LOB;
    }

}
