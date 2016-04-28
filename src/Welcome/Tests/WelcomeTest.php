<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/12/6
 * Time: 下午9:38
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Welcome\Tests;

use FastD\Framework\Tests\WebTestCase;

class WelcomeTest extends WebTestCase
{
    public function testIndex()
    {
        $client = self::createClient();// 模拟请求
        $response = $client->testResponse('GET', '/');
        $this->assertEquals('hello fastd', $response->getContent());
    }
}