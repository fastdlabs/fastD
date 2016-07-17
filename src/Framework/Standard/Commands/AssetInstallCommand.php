<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/7/22
 * Time: 上午10:07
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Standard\Commands;

use FastD\Console\Input\Input;
use FastD\Console\Input\InputArgument;
use FastD\Console\Output\Output;

/**
 * Class AssetInstall
 *
 * @package FastD\Framework\Commands
 */
class AssetInstallCommand extends CommandAware
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'asset:install';
    }

    /**
     * php bin/console asset:install WelcomeBundle
     */
    public function configure()
    {
        $this->setArgument('bundle', InputArgument::OPTIONAL, 'bundle 包名(WelcomeBundle)');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @throws \Exception
     * @return void
     */
    public function execute(Input $input, Output $output)
    {
        $kernel = $this->getContainer()->singleton('kernel');

        $bundles = $kernel->getBundles();
        
        $web = 'public/bundles';

        $targetRootDir = $kernel->getRootPath() . '/../' . $web;

        $output->writeln('Trying to install assets as symbolic links.');

        foreach ($bundles as $bundle) {
            $originDir = $bundle->getRootPath() . DIRECTORY_SEPARATOR . 'Resources/assets';
            if (!file_exists($originDir)) {
                continue;
            }

            $targetDir = $targetRootDir . DIRECTORY_SEPARATOR . strtolower($bundle->getShortName());

            try {
                $this->symlink($originDir, $targetDir);
                $output->write('Installing assets for ');
                $output->write(sprintf('<success>%s</success>', $bundle->getName()));
                $output->write(' into ');
                $output->writeln($web . DIRECTORY_SEPARATOR . sprintf('<success>%s</success>', strtolower($bundle->getShortName())));
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    /**
     * @param $originDir
     * @param $targetDir
     */
    public function symlink($originDir, $targetDir)
    {
        $this->mkdir(dirname($targetDir));

        $ok = false;
        if (is_link($targetDir)) {
            if (readlink($targetDir) != $originDir) {
                $this->remove($targetDir);
            } else {
                $ok = true;
            }
        }

        if (!$ok) {
            if (true !== @symlink($originDir, $targetDir)) {
                $report = error_get_last();
                if (is_array($report)) {
                    if ('\\' === DIRECTORY_SEPARATOR && false !== strpos($report['message'], 'error code(1314)')) {
                        throw new \RuntimeException('Unable to create symlink due to error code 1314: \'A required privilege is not held by the client\'. Do you have the required Administrator-rights?');
                    }
                }
                throw new \RuntimeException(sprintf('Failed to create symbolic link from "%s" to "%s".', $originDir, $targetDir), 0, null, $targetDir);
            }
        }
    }

    /**
     * @param $files
     */
    public function remove($files)
    {
        $files = iterator_to_array($this->toIterator($files));
        $files = array_reverse($files);
        foreach ($files as $file) {
            if (!file_exists($file) && !is_link($file)) {
                continue;
            }

            if (is_dir($file) && !is_link($file)) {
                $this->remove(new \FilesystemIterator($file));

                if (true !== @rmdir($file)) {
                    throw new \RuntimeException(sprintf('Failed to remove directory "%s".', $file), 0, null, $file);
                }
            } else {
                // https://bugs.php.net/bug.php?id=52176
                if ('\\' === DIRECTORY_SEPARATOR && is_dir($file)) {
                    if (true !== @rmdir($file)) {
                        throw new \RuntimeException(sprintf('Failed to remove file "%s".', $file), 0, null, $file);
                    }
                } else {
                    if (true !== @unlink($file)) {
                        throw new \RuntimeException(sprintf('Failed to remove file "%s".', $file), 0, null, $file);
                    }
                }
            }
        }
    }

    /**
     * @param $dirs
     * @param int $mode
     */
    public function mkdir($dirs, $mode = 0755)
    {
        foreach ($this->toIterator($dirs) as $dir) {
            if (is_dir($dir)) {
                continue;
            }

            if (true !== @mkdir($dir, $mode, true)) {
                $error = error_get_last();
                if (!is_dir($dir)) {
                    // The directory was not created by a concurrent process. Let's throw an exception with a developer friendly error message if we have one
                    if ($error) {
                        throw new \RuntimeException(sprintf('Failed to create "%s": %s.', $dir, $error['message']), 0, null, $dir);
                    }
                    throw new \RuntimeException(sprintf('Failed to create "%s"', $dir), 0, null, $dir);
                }
            }
        }
    }

    /**
     * @param mixed $files
     *
     * @return \Traversable
     */
    private function toIterator($files)
    {
        if (!$files instanceof \Traversable) {
            $files = new \ArrayObject(is_array($files) ? $files : array($files));
        }

        return $files;
    }

    /**
     * @param $originDir
     * @param $targetDir
     * @param \Traversable|null $iterator
     * @param array $options
     */
    public function mirror($originDir, $targetDir, \Traversable $iterator = null, $options = array())
    {
        $targetDir = rtrim($targetDir, '/\\');
        $originDir = rtrim($originDir, '/\\');

        // Iterate in destination folder to remove obsolete entries
        if ($this->exists($targetDir) && isset($options['delete']) && $options['delete']) {
            $deleteIterator = $iterator;
            if (null === $deleteIterator) {
                $flags = \FilesystemIterator::SKIP_DOTS;
                $deleteIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($targetDir, $flags), \RecursiveIteratorIterator::CHILD_FIRST);
            }
            foreach ($deleteIterator as $file) {
                $origin = str_replace($targetDir, $originDir, $file->getPathname());
                if (!$this->exists($origin)) {
                    $this->remove($file);
                }
            }
        }

        $copyOnWindows = false;
        if (isset($options['copy_on_windows'])) {
            $copyOnWindows = $options['copy_on_windows'];
        }

        if (null === $iterator) {
            $flags = $copyOnWindows ? \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS : \FilesystemIterator::SKIP_DOTS;
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($originDir, $flags), \RecursiveIteratorIterator::SELF_FIRST);
        }

        if ($this->exists($originDir)) {
            $this->mkdir($targetDir);
        }

        foreach ($iterator as $file) {
            $target = str_replace($originDir, $targetDir, $file->getPathname());

            if ($copyOnWindows) {
                if (is_link($file) || is_file($file)) {
                    $this->copy($file, $target, isset($options['override']) ? $options['override'] : false);
                } elseif (is_dir($file)) {
                    $this->mkdir($target);
                } else {
                    throw new \RuntimeException(sprintf('Unable to guess "%s" file type.', $file), 0, null, $file);
                }
            } else {
                if (is_link($file)) {
                    $this->symlink($file->getRealPath(), $target);
                } elseif (is_dir($file)) {
                    $this->mkdir($target);
                } elseif (is_file($file)) {
                    $this->copy($file, $target, isset($options['override']) ? $options['override'] : false);
                } else {
                    throw new \RuntimeException(sprintf('Unable to guess "%s" file type.', $file), 0, null, $file);
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return '生成资源目录 {Bundle}/Resources/assets 软连接到 public/bundles 目录, 可以通过 [<bundle>] 参数进行指定';
    }
}