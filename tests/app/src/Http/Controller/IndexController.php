<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Http\Controller;

use FastD\Controller\Controller;

class IndexController extends Controller
{
    public function welcomeAction()
    {
        return response()->withContent('hello world');
    }
}