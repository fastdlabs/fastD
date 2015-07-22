<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/14
 * Time: 下午3:23
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Welcome\Events;

use FastD\Framework\Events\TemplateEvent;
use FastD\Http\Request;

/**
 * Class Index
 *
 * @package Welcome\Events
 */
class Index extends TemplateEvent
{
    public function welcomeAction(Request $request)
    {
        return $request->createRequest('http://www.fast-d.cn/')->get();
    }
}