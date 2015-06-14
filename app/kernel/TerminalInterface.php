<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/10
 * Time: 下午12:29
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Kernel;

use Dobee\Protocol\Http\Request;
use Dobee\Protocol\Http\Response;

/**
 * Interface TerminalInterface
 *
 * @package Dobee\Framework
 */
interface TerminalInterface
{
    /**
     * @param Request  $request
     * @param Response $response
     * @return mixed
     */
    public function terminate(Request $request, Response $response);
}