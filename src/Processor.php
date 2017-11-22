<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD;


use FastD\ServiceProvider\ProcessorServiceProvider;

/**
 * Class Processor
 * @package FastD
 */
class Processor
{
    public function __construct(Application $application)
    {
        $application->register(new ProcessorServiceProvider());
    }

    public function run()
    {

    }
}