<?php

use Phinx\Seed\AbstractSeed;

class Demo extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $table = $this->table('user_logins');
        $table->addColumn('user_id', 'integer')
            ->addColumn('created', 'datetime')
            ->create();
    }
}
