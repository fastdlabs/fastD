<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/25
 * Time: 上午9:48
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Kernel\Template;

use Dobee\Container\Container;
use Dobee\Template\Template;

/**
 * Class TemplateEngine
 *
 * @package Dobee\Framework\Bridges\Template
 */
class TemplateEngine extends Template
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $config = $container->get('kernel.config');

        try {
            $options = array(
                'paths'     => str_replace('%root.path%', $config->getVariable('root.path'), $config->getParameters('template.paths'))
            );
        } catch (\Exception $e) {
            $options = array(
                'paths'     => array(__DIR__),
            );
        }

        try {
            $options['options'] = array('cache' => $config->getParameters('template.options.cache'));
        } catch (\Exception $e) {
            $options['options'] = array();
        }

        try {
            $engine = $config->getParameters('template.engine');
        } catch (\Exception $e) {
            $engine = 'twig';
        }

        parent::__construct(
            $container->get('kernel')->getDebug(),
            $options,
            $engine
        );
    }

    public function registerGlobal()
    {
        return array(
            'request' => $this->container->get('kernel.request'),
        );
    }

    public function registerExtensions()
    {
        return array(
            'path'  => new \Twig_SimpleFunction('path', function ($path, array $parameters = array()) {
                return $this->container->get('kernel.routing')->generateUrl($path, $parameters);
            }),
            'asset' => new \Twig_SimpleFunction('asset', function ($asset) {
                try {
                    $host = $this->container->get('kernel.config')->getParameters('assets.host');
                } catch (\InvalidArgumentException $e) {
                    $host = $this->container->get('kernel.request')->getHttpAndHost();
                }

                try {
                    $path = $this->container->get('kernel.config')->getParameters('assets.path');
                } catch (\InvalidArgumentException $e) {
                    $path = $this->container->get('kernel.request')->getBaseUrl();

                    if ('' != pathinfo($path, PATHINFO_EXTENSION)) {
                        $path = pathinfo($path, PATHINFO_DIRNAME);
                    }
                }

                return $host . str_replace('//', '/', $path . '/' . $asset);
            }),
        );
    }
}