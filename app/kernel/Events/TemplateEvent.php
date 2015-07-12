<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/30
 * Time: 下午2:55
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Kernel\Events;

use FastD\Template\Template;

/**
 * Class TemplateEvent
 *
 * @package Kernel\Events
 */
class TemplateEvent extends BaseEvent
{
    /**
     * @var Template
     */
    protected $template;

    /**
     * render template show page.
     *
     * @param string $template template content string or template file path.
     * @param array $parameters
     * @return string
     */
    public function render($template, array $parameters = array())
    {
        if (null === $this->template) {
            $paths = $this->getParameters('template.paths');
            $options = [];
            if (!($isDebug = $this->container->get('kernel')->isDebug())) {
                $options = [
                    'cache' => $this->getParameters('template.cache'),
                    'debug' => $isDebug,
                ];
            }
            $self = $this;
            $this->template = $this->container->get('kernel.template', [$paths, $options]);
            $this->template->addGlobal('request', $this->getRequest());
            $this->template->addFunction('url', new \Twig_SimpleFunction('url', function ($name, array $parameters = [], $suffix = false) use ($self) {
                return $self->url($name, $parameters, $suffix);
            }));
            $this->template->addFunction('asset', new \Twig_SimpleFunction('asset', function ($name, $host = null, $path = null) use ($self) {
                return $self->asset($name, $host, $path);
            }));
            unset($paths, $options);
        }

        return $this->template->render($template, $parameters);
    }

    /**
     * @param $name
     * @param $parameters
     * @param $suffix
     * @return string
     */
    protected function url($name, array $parameters = null, $suffix = false)
    {
        return $this->generateUrl($name, $parameters, $suffix);
    }

    /**
     * @param      $name
     * @param null $host
     * @param null $path
     * @return string
     */
    protected function asset($name, $host = null, $path = null)
    {
        if (null === $host) {
            try {
                $host = $this->getParameters('assets.host');
            } catch (\InvalidArgumentException $e) {
                $host = '//' . $this->getRequest()->getDomain();
            }
        }

        if (null === $path) {
            try {
                $path = $this->getParameters('assets.path');
            } catch (\InvalidArgumentException $e) {
                $path = $this->getRequest()->getRootPath();

                if ('' != pathinfo($path, PATHINFO_EXTENSION)) {
                    $path = pathinfo($path, PATHINFO_DIRNAME);
                }
            }
        }

        return $host . str_replace('//', '/', $path . '/' . $name);
    }
}