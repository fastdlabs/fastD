<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */


namespace WelcomeBundle\Controllers;

use FastD\Framework\Bundle\Controllers\Controller;
use FastD\Packet\Binary;
use FastD\Packet\Json;

class PacketController extends Controller
{
    /**
     * @Route("/packet")
     *
     * @return \FastD\Http\Response
     */
    public function binaryAction()
    {
        return $this->response(Binary::encode(['hello world']));
    }
}