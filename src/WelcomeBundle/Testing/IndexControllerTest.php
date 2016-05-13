<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/5/13
 * Time: 下午11:42
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace WelcomeBundle\Testing;

use FastD\Framework\Tests\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();

        $response = $client->testResponse('GET', '/welcomebundle/');

        $this->assertEquals(200, $response->getStatusCode());
    }
}