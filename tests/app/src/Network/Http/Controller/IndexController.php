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
    public function welcome()
    {
        return response()->withContent('hello world');
    }

    public function store()
    {
        $store = store('user');

//        $store->getUser();

        echo '<pre>';
        return response()->withContent(var_dump($store));
    }

    public function event()
    {

    }
}