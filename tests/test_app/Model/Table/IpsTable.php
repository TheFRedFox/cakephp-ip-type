<?php

namespace TestApp\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Cake\ORM\Table;

class IpsTable extends Table
{

    public function initialize(array $config)
    {
        $this->table('ips');
        $this->displayField('id');
        $this->primaryKey('id');
    }

    protected function _initializeSchema(Schema $table)
    {
        $table->columnType('ip', 'ip');

        return parent::_initializeSchema($table);
    }

}