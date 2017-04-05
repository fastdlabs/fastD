
<?php

use FastD\Model\Migration;
use Phinx\Db\Table;

class Hello extends Migration
{
    /**
     * Set up database table schema
     */
    public function setUp()
    {
        return $this->table('');
    }

    public function dataSet(Table $table)
    {

    }
}