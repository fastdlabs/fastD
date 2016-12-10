<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Http\Controller;

use FastD\Http\Response;

class IndexController
{
    /**
     * @route("/", name="demo")
     *
     * @return Response
     */
    public function welcomeAction()
    {
        return json([
            'foo' => 'bar'
        ]);
    }
}