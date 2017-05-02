<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Logger;


use Monolog\Handler\AbstractProcessingHandler;

/**
 * Class ErrorHandler
 * @package FastD\Logger
 */
class ErrorHandler extends AbstractProcessingHandler
{
    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record = [])
    {
        $record = array_merge(
            $record,
            [
                'message' => request()->getMethod() . ' ' . request()->getUri()->getPath(),
                'context' => [
                    'ip' => get_local_ip(),
                    'status' => response()->getStatusCode(),
                    'get' => $request->getQueryParams(),
                    'post' => $request->getParsedBody(),
                ],
            ]
        );
        print_r($record);
    }
}