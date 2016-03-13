<?php

namespace IpType\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Short description for class.
 *
 */
class IpsFixture extends TestFixture
{

    public $table = 'ips';

    /**
     * fields property
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer'],
        'ip' => ['type' => 'binary', 'length' => 16, 'null' => true],
        'type' => ['type' => 'string', 'length' => 16, 'null' => true],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]]
    ];

    public function __construct($connection = null)
    {
        $this->records = [
            ['id' => 1, 'ip' => inet_pton('127.0.0.1'), 'type' => 'default'],
            ['id' => 2, 'ip' => inet_pton('::1'), 'type' => 'default'],
            ['id' => 3, 'ip' => ip2long('127.0.0.1'), 'type' => 'methodName'],
            ['id' => 4, 'ip' => ip2long('::1'), 'type' => 'methodName'],
            ['id' => 5, 'ip' => '127.0.0.11', 'type' => 'callable'],
            ['id' => 6, 'ip' => '::11', 'type' => 'callable'],
            ['id' => 7, 'ip' => inet_pton('192.168.178.1'), 'type' => 'find'],
        ];

        if ($connection) {
            $this->connection = $connection;
        }
        $this->init();
    }


}
