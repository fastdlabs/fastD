<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/30
 * Time: 上午9:53
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

Routes::get(['/di', 'name' => 'di'], 'Welcome\Events\Index@pluginAction');
