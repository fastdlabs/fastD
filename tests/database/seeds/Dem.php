
<?php

use Phinx\Migration\AbstractMigration;

class Dem extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        if (!$this->hasTable('user_logins')) {
            $table = $this->table('user_logins', ['id' => false]);
            $table
                ->addColumn('user_id', 'integer')
                ->addColumn('created', 'datetime')
                ->create()
            ;
        }
    }

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->insert('user_logins', [
            [
                'user_id' => 1,
                'created' => date('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
    
    }
}