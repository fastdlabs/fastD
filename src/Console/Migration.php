<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Console;


use FastD\Migration\Migrate;

class Migration extends Migrate
{
    public function configure()
    {
        parent::configure();
        $this->addOption('connection', '');
    }
}