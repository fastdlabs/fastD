<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Test;


use FastD\Application;
use FastD\Testing\WebTestCase;

/**
 * Class TestCase
 * @package FastD\Test
 */
class TestCase extends WebTestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Set up unit.
     */
    public function setUp()
    {
        $this->app = $this->createApplication();
    }

    /**
     * @return Application
     */
    public function createApplication()
    {
        return new Application(getcwd());
    }
}