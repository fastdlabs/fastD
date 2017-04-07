<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Model;

use Phinx\Db\Table;
use Phinx\Migration\AbstractMigration;

/**
 * Class Migration.
 */
abstract class Migration extends AbstractMigration
{
    public function change()
    {
        $table = $this->setUp();
        if (!$table->exists()) {
            $table->create();
        }
        $this->dataSet($table);
    }

    /**
     * @return Table
     */
    abstract public function setUp();

    /**
     * @param Table $table
     *
     * @return mixed
     */
    abstract public function dataSet(Table $table);
}
