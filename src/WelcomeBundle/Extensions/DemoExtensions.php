<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/25
 * Time: 下午10:55
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace WelcomeBundle\Extensions;

use FastD\Framework\Extensions\TplExtension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class DemoExtensions extends TplExtension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'demo';
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('demo_filter', function () {
                return 'demo_filter';
            })
        ];
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('demo_func', function () {
                return 'demo_func';
            }),
        ];
    }
}