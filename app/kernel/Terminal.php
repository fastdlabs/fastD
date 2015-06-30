<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/30
 * Time: 下午3:58
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Kernel;

use FastD\Console\Environment\BaseEnvironment;

abstract class Terminal extends BaseEnvironment implements TerminalInterface, AppKernelInterface
{
    public function register()
    {
        $bundles = array_merge($this->getBundles(), [new Bundle()]);
        foreach ($bundles as $bundle) {
            if ($dh = opendir($bundle->getRootPath() . '/Commands')) {
                while (($file = readdir($dh)) !== false) {
                    if (in_array($file, ['.', '..'])) {
                        continue;
                    }
                    $fileName = $bundle->getNamespace() . '\\Commands\\' . pathinfo($file, PATHINFO_FILENAME);
                    $this->setCommand(new $fileName);
                }
                closedir($dh);
            }
        }
    }
}