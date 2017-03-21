<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Model;


use Phinx\Migration\AbstractMigration;

/**
 * Class Migration
 * @package FastD\Model
 */
abstract class Migration extends AbstractMigration
{
    public function change()
    {
        $this->up();
        $this->dateSet();
    }

    public function dateSet()
    {}
}