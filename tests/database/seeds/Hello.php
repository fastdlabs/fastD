
<?php

use FastD\Model\Migration;

class Hello extends Migration
{
    /**
     * Set up database table schema
     */
    public function setUp()
    {
        echo 'up';
    }

    /**
     * Insert into data set in table
     */
    public function dataSet()
    {
        echo 'data set';
    }

    /**
     * delete data or truncate table
     */
    public function tearDown()
    {
        echo 'down';
    }
}