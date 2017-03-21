
<?php

use FastD\Model\Migration;

class Hello extends Migration
{
    /**
     * Set up database table schema
     */
    public function up()
    {
        echo 'up' . PHP_EOL;
    }

    /**
     * delete data or truncate table
     */
    public function down()
    {
        echo 'down' . PHP_EOL;
    }
}