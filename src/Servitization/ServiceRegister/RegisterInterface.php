<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Servitization\ServiceRegister;

use FastD\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;


/**
 * Interface RegisterInterface
 * @package FastD\Servitization\ServiceRegister
 */
interface RegisterInterface
{
    /**
     * @param ServerRequest $request
     * @return ResponseInterface
     */
    public function query(ServerRequest $request);

    /**
     * @param ServerRequest $request
     * @return ResponseInterface
     */
    public function publish(ServerRequest $request);
}