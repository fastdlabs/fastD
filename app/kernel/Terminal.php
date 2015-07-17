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
            $dir = $bundle->getRootPath() . '/Commands';
            if (!is_dir($dir)) {
                continue;
            }
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (in_array($file, ['.', '..']) || is_dir($dir. DIRECTORY_SEPARATOR . $file)) {
                        continue;
                    }
                    $fileName = $bundle->getNamespace() . '\\Commands\\' . pathinfo($file, PATHINFO_FILENAME);
                    $command = new $fileName();
                    $command->setEnv($this);
                    $this->setCommand($command);
                }
                closedir($dh);
            }
        }
    }
}