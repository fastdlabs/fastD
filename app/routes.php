<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/27
 * Time: 下午12:07
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

Routes::get(['/', 'name' => 'welcome'], function () {
    return Make::template('welcome/welcome.html.twig');
});

Routes::get(['/p', 'name' => 'plugins'], 'Welcome\\Events\Index@pluginAction');

Routes::get(['/d', 'name' => 'di'], 'Welcome\\Events\Index@pluginsDIAction');

Routes::get(['/c', 'name' => 'db'], 'Welcome\\Events\DB@dbAction');

Routes::get(['/r', 'name' => 'route'], 'Welcome\\Events\Route@showRoute');

Routes::get(['/v', 'name' => 'view'], 'Welcome\\Events\View@show');