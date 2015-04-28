<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/11
 * Time: 下午11:16
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Framework\Bridges\Config;

class Config extends \Dobee\Config\Config
{
    private $cacheName = 'config.php.cache';

    public function getCacheName()
    {
        return $this->cacheName;
    }

    public function __toString()
    {
        $parameters = array();

        $recursion = function ($parent = array(), array $config) use (&$parameters, &$recursion) {
            foreach ($config as $key => $val) {

                if (is_array($val)) {
                    $parent[] = $key;
                    $recursion($parent, $val);
                    array_pop($parent);
                } else {
                    if (!empty($parent)) {
                        $key = implode('.', $parent) . '.' . $key;
                    }
                    try {
                        $parameters[$key] = $this->getParameters($key);
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }
        };

        $recursion(array(), $this->parameters);

        $parameters = array_merge($parameters, $this->parameters);

        $parameters = var_export($parameters, true);

        $keys = array_map(function ($value) {
            return '%' . $value . '%';
        }, array_keys($this->variable->getVariables()));

        $parameters = str_replace($keys, $this->variable->getVariables(), $parameters);

        return (string)$parameters;
    }

    public function loadCache($path)
    {
        $path = $path . DIRECTORY_SEPARATOR . $this->cacheName;

        if (file_exists($path)) {

            $this->setParameters(include $path);

            return true;
        }

        return false;
    }
}