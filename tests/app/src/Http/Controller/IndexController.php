<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Http\Controller;

class IndexController
{
    public function welcomeAction()
    {
        return response()->withContent('hello world');
    }

    public function storeAction()
    {
        $store = store('user');

        echo '<pre>';
        return response()->withContent(var_dump($store));
    }

    public function eventAction()
    {

    }
}