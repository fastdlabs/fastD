<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/30
 * Time: 下午2:56
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Kernel\Events;

use FastD\Http\Response;
use FastD\Http\JsonResponse;

/**
 * Class REST
 *
 * @package Kernel\Extensions
 */
class RestEvent extends BaseEvent
{
    /**
     * @var string
     */
    protected $version = 'v1';

    /**
     * @var string
     */
    protected $title = 'fast-d';

    /**
     * @var string
     */
    protected $accept = 'application/vnd.%s.%s+json';

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string
     */
    protected $allowOrigin = '*';

    /**
     *
     */
    public function __construct()
    {
        $this->headers = [
            'Version'   => $this->getVersion(),
            'X-' . strtoupper($this->getTitle()) . '-Media-Type' => strtolower($this->getTitle() . '.' . $this->getVersion()),
        ];
    }

    /**
     * @return null
     */
    public function getAllowOrigin()
    {
        return $this->allowOrigin;
    }

    /**
     * @param null $allowOrigin
     * @return $this
     */
    public function setAllowOrigin($allowOrigin)
    {
        $this->allowOrigin = $allowOrigin;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param array $responseData
     * @param int   $status
     * @param array $headers
     * @return JsonResponse
     */
    public function responseJson(array $responseData, $status  = Response::HTTP_OK, array $headers = array())
    {
        $headers['Status'] = sprintf('%s %s', $status, Response::$statusTexts[$status]);
        $headers['Access-Control-Allow-Origin'] = null === ($allowOrigin = $this->getAllowOrigin()) ? '*' : $allowOrigin;
        $headers['Access-Control-Allow-Credentials'] = true;
        $headers['Access-Control-Expose-Headers'] = 'ETag, Link';

        return new JsonResponse($responseData, $status, array_merge(
            $this->headers,
            $headers
        ));
    }

    /**
     * @param $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}