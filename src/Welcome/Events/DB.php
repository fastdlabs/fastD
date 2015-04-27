<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/27
 * Time: 下午4:59
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Welcome\Events;

use Dobee\Framework\Bundle\Events\EventAbstract;

class DB extends EventAbstract
{
    public function dbAction()
    {
        $read = $this->getConnection('read');

        return 'DB connection: ' . get_class($read);
    }
}