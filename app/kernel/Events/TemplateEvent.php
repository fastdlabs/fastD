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

class TemplateEvent extends EventAbstract
{
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
            $this->template = $this->container->get('kernel.template', [$paths, $options]);
            unset($paths, $options);
        }

        return $this->template->render($template, $parameters);
    }
}