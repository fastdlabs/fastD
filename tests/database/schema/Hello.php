
<?php

use FastD\Model\Migration;
use Phinx\Db\Table;

class Hello extends Migration
{
    /**
     * Set up database table schema.
     */
    public function setUp()
    {
        $table = $this->table('hello');
        $table
            ->addColumn('content', 'string')
            ->addColumn('user', 'string')
            ->addColumn('created', 'datetime')
        ;

        return $table;
    }

    public function dataSet(Table $table)
    {
    }
}
