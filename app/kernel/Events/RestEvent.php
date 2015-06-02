<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/2
 * Time: 下午7:01
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Kernel\Events;

use Dobee\Http\JsonResponse;
use Dobee\Http\Response;

/**
 * Class RestEvent
 *
 * @package Kernel\Events
 */
class RestEvent extends EventAbstract
{
    /**
     * @var string
     */
    protected $version = 'v1';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $accept = 'application/vnd.%s.%s+json';

    /**
     * @var array
     */
    protected $headers = [];

    /**
     *
     */
    public function __construct()
    {
        $this->headers = [
            'Accept'    => sprintf($this->getAccept(), $this->getTitle(), $this->getVersion()),
            'Version'   => $this->getVersion(),
        ];
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

    /**
     * @param $accept
     * @return $this
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccept()
    {
        return $this->accept;
    }
}