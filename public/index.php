<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/7
 * Time: 上午1:57
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

$loader = include __DIR__ . '/../vendor/autoload.php';

use App\AppKernel;
use Dobee\Kernel\Configuration\HttpFoundation\Request;

$appKernel = new AppKernel('dev', true);

$request = Request::createGlobalRequest();

$response = $appKernel->handleRequest($request);

$response->send();

$appKernel->terminate($request, $response);

