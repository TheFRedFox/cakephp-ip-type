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

## Database
The final converted value for the database is meant to be stored as a LOB value. I used a VARBINARY with 16 bytes. It is long enough for also IPv6 addresses.
