<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Servitization\Client;

use FastD\Http\Response;

/**
 * Class Consumer.
 */
class Client extends \FastD\Swoole\Client
{
    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->scheme;
    }

    /**
     * @param string $data
     *
     * @return Response
     */
    public function send($data = '')
    {
        $response = parent::send($data);

        return $this->wrapResponse($response);
    }

    /**
     * @param $response
     *
     * @return Response
     */
    protected function wrapResponse($response)
    {
        $statusCode = 200;
        $headers = [];

        if (false !== (strpos($response, "\r\n\r\n")) && false !== strpos($this->scheme, 'http')) {
            list($responseHeaders, $response) = explode("\r\n\r\n", $response, 2);
            $responseHeaders = preg_split('/\r\n/', $responseHeaders, null, PREG_SPLIT_NO_EMPTY);

            $code = array_shift($responseHeaders);
            list(, $statusCode) = explode(' ', $code);
            $headers = [];
            array_map(function ($headerLine) use (&$headers) {
                list($key, $value) = explode(':', $headerLine, 2);
                $headers[$key] = trim($value);
                unset($headerLine, $key, $value);
            }, $responseHeaders);

            if (isset($headers['Content-Encoding'])) {
                $response = zlib_decode($response);
            }
            unset($responseHeaders, $code);
        }

        return new Response($response, $statusCode, $headers);
    }
}
