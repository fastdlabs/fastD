<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/22
 * Time: 下午8:26
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace OfficialBundle\Controllers;

use Dobee\Framework\Controller\Controller;
use Dobee\Routing\Router;

class BaseController extends Controller
{
    protected $routes = array();

    protected $currentRoute;

    public function __construct(Router $router)
    {
        $this->routes = array(
            '首页'        => $router->getRoute('web_index'),
            '文档'        => $router->getRoute('docs_index'),
            '博客'        => $router->getRoute('blog_index'),
            '成功案例'     => $router->getRoute('web_showcase'),
            '关于'        => $router->getRoute('web_about'),
        );

        $this->currentRoute = $router->getCurrentRoute();
    }

    /**
     * @param string $template
     * @param array  $parameters
     * @return string
     */
    public function render($template, array $parameters = array())
    {
        $parameters = array_merge($parameters, array('routes' => $this->routes, 'current' => $this->currentRoute));

        return parent::render($template, $parameters);
    }
}