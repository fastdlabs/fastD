<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Welcome\Controller;

use FastD\Controller\Controller;

class IndexController extends Controller
{
    public function welcomeAction()
    {
        $db = db('name');

        $redis = storage('redis');

        $user = store('user')->getUser();

        $result = event('axx')->trigger();

        return response();
    }
}