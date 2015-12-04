<?php
/**
 * @author TheFRedFox <thefredfox@googlemail.com>
 *
 * An Ip Type for the Database Framework of CakePHP, which converts raw ip addresses represented as human readable strings (127.0.0.1, ::1) to byte strings with inet_pton to store them into a database.
 * The marshal function does nothing as it is usual that raw ip address strings are given in data, so nothing has to be converted in this state.
 *
 * The final converted value for the database is meant to be stored as a LOB value. I used a VARBINARY with 16 bytes. It is long enough for also IPv6 addresses.
 *
 * The class was written for an application for InnoGames GmbH ( http://www.innogames.com ). The permission was given to make this class public.
 */
namespace IpType\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type;
use PDO;

class IpType extends Type
{

    public function toPHP($value, Driver $driver)
    {
        if ($value === null)
        {
            return null;
        }

        return inet_ntop($value);
    }

    public function marshal($value)
    {
        return $value;
    }

    public function toDatabase($value, Driver $driver)
    {
        if ($value === null)
        {
            return null;
        }

        return inet_pton($value);
    }

    public function toStatement($value, Driver $driver)
    {
        if ($value === null)
        {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_LOB;
    }

}
