<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/8
 * Time: 下午11:39
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace DemoBundle\Repository;

use Dobee\Kernel\Framework\Repository\Repository;

class PostRepository extends Repository
{
    public function getPost()
    {
        return $this
            ->createQuery("select * from sf_post where id = :id")
            ->setParameters('id', 1)
            ;
    }
}