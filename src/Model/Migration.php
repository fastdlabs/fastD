<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Model;

use Phinx\Db\Table;
use Phinx\Db\Table\Column;
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
            $hasAvailable = $hasCreatedColumn = $hasUpdatedColumn = false;
            array_map(
                function (Column $column) use (&$hasCreatedColumn, &$hasAvailable, &$hasUpdatedColumn) {
                    if ('is_available' === $column->getName()) {
                        $hasAvailable = true;
                        return;
                    }
                    if ('created' === $column->getName()) {
                        $hasCreatedColumn = true;

                        return;
                    }
                    if ('updated' === $column->getName()) {
                        $hasUpdatedColumn = true;

                        return;
                    }
                },
                $table->getPendingColumns()
            );
            !$hasAvailable && $table->addColumn('is_available', 'boolean');
            !$hasCreatedColumn && $table->addColumn('created', 'datetime');
            !$hasUpdatedColumn && $table->addColumn('updated', 'datetime');

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
