<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD;

use FastD\Http\JsonResponse;
use FastD\Http\Response;
use FastD\Http\ServerRequest;
use PHPUnit\Framework\TestCase as PHPUnit;

/**
 * Class TestCase.
 */
class TestCase extends PHPUnit
{
    /**
     * @param $method
     * @param $path
     * @param array $headers
     * @return ServerRequest
     */
    public function createRequest(string $method, string $path, array $headers = []): ServerRequest
    {
        return new ServerRequest($method, $path, $headers);
    }

    /**
     * @param Response $response
     * @param $assert
     */
    public function response(Response $response, $assert): void
    {
        $this->equalsResponse($response, $assert);
    }

    /**
     * @param Response $response
     * @param $assert
     */
    public function equalsResponse(Response $response, $assert): void
    {
        static::assertEquals((string) $response->getBody(), $assert);
    }

    /**
     * @deprecated
     * @param Response $response
     * @param array $assert
     */
    public function json(Response $response, array $assert): void
    {
        $this->equalsJson($response, $assert);
    }

    /**
     * @param Response $response
     * @param array $assert
     */
    public function equalsJson(Response $response, array $assert): void
    {
        static::assertEquals((string) $response->getBody(), json_encode($assert, JsonResponse::JSON_OPTIONS));
    }

    /**
     * @param Response $response
     * @param string $key
     */
    public function equalsJsonResponseHasKey(Response $response, string $key): void
    {
        $json = (string) $response->getBody();
        $array = json_decode($json, true);
        if (is_string($key)) {
            $keys = [$key];
        } else {
            $keys = $key;
        }
        foreach ($keys as $key) {
            static::assertArrayHasKey($key, $array);
        }
    }

    /**
     * @param Response $response
     * @param $statusCode
     */
    public function equalsStatus(Response $response, $statusCode): void
    {
        static::assertEquals($response->getStatusCode(), $statusCode);
    }

    /**
     * @param Response $response
     */
    public function isServerInterval(Response $response): void
    {
        static::assertEquals(500, $response->getStatusCode());
    }

    /**
     * @param Response $response
     */
    public function isBadRequest(Response $response): void
    {
        static::assertEquals(400, $response->getStatusCode());
    }

    /**
     * @param Response $response
     */
    public function isNotFound(Response $response): void
    {
        static::assertEquals(404, $response->getStatusCode());
    }

    /**
     * @param Response $response
     */
    public function isSuccessful(Response $response): void
    {
        static::assertEquals(200, $response->getStatusCode());
    }
}