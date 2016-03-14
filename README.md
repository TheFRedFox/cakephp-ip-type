# IpType plugin for CakePHP

## Description
An Ip Type for the Database Framework of CakePHP, which converts raw ip addresses represented as human readable strings (127.0.0.1, ::1) to byte strings with inet_pton to store them into a database.

The final converted value for the database is meant to be stored as a LOB value. I used a VARBINARY with 16 bytes. It is long enough for also IPv6 addresses.

The class was written for an application for [InnoGames GmbH]( http://www.innogames.com). The permission was given to make this class public.

## Installation
You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

``` php
composer require thefredfox/cakephp-ip-type
```

After that you have to load the plugin in your application's bootstrap file and map the type for the database as follows:

``` php
// in bootstrap file

Plugin::load('IpType');
Type::map('ip', 'IpType\Database\Type\IpType');
```

In the Table class itself you have to tell the column to be this type:

``` php
// in your Entity Table class (eg. UsersTable)

use Cake\Database\Schema\Table as Schema;

protected function _initializeSchema(Schema $schema) {
    $schema->columnType('ip', 'ip');
    return $schema;
}
```

## Configuration
The default function for encoding and decoding is 'inet_pton' resp. 'inet_ntop'. However it is possible to set the encode (toDatabase) and decode (toPHP) methods as callables.

Is the variable not a callable an ```\UnexpectedValueException``` will be thrown with the message: 'Could not decode the value, IpType::_decode has to be a callable.' resp. '... encode ... IpType::_encode ...'.

### Method Strings
``` php
// in bootstrap file

/** @var IpType $ipType */
$ipType = Type::build('ip');
$ipType->_encode = 'ip2long'; // using global ip2long method for encoding (just IPv4 support)
$ipType->_decode = 'long2ip'; // using global long2ip method for decoding (just IPv4 support)
```

### Custom Functions
``` php
// in bootstrap file

/** @var IpType $ipType */
$ipType = Type::build('ip');
$ipType->_encode = function ($value) {
    return $value . '1'; // just concatenate a '1' at the end, for what reason ever
};
$ipType->_decode = function ($value) {
    return $value;
};
```

### Static Functions of a Class
``` php
class TestClass {
    public static function staticEncode($value) { /*...*/ }
    public static function staticDecode($value) { /*...*/ }
}

/** @var IpType $ipType */
$ipType = Type::build('ip');
$ipType->_encode = 'TestClass::staticEncode';
$ipType->_decode = 'TestClass::staticDecode';
```

### Non Static Functions of a Class via Wrapper Function
``` php
class TestClass {
    public function encode($value) { /*...*/ }
    public function decode($value) { /*...*/ }
}

/** @var IpType $ipType */
$ipType = Type::build('ip');
$ipType->_encode = function ($value) {
   $object = new TestClass();
   return $object->encode($value);
};
$ipType->_decode = function ($value) {
   $object = new TestClass();
   return $object->decode($value);
};
```

## Database
The final converted value for the database is meant to be stored as a LOB value. I used a VARBINARY with 16 bytes. It is long enough for also IPv6 addresses.
