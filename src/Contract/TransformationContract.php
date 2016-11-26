<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Contract;

abstract class TransformationContract
{
    /**
     * @param array $data
     * @return array
     */
    abstract public function format(array $data);
}