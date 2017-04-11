<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Model;


use Medoo\Medoo;

/**
 * Class Database
 * @package Model
 */
class Database extends Medoo
{
    /**
     * @var array|null
     */
    protected $config = [];

    /**
     * Database constructor.
     * @param null $options
     */
    public function __construct($options = null)
    {
        $this->config = $options;

        parent::__construct($this->config);
    }

    /**
     * reconnect database
     */
    public function reconnect()
    {
        $this->__construct($this->config);
    }

    /**
     * check database gone away
     */
    public function checkGoneAway()
    {
        list(, $code,) = $this->pdo->errorInfo();
        if (2006 == $code || 2013 == $code) {
            $this->reconnect();
        }
    }

    /**
     * @param $query
     * @return bool|\PDOStatement
     */
    public function query($query)
    {
        $result = parent::query($query);
        if (false === $result) {
            $this->checkGoneAway();
            $result = parent::query($query);
        }

        return $result;
    }

    /**
     * @param $query
     * @return bool|int
     */
    public function exec($query)
    {
        $result = parent::exec($query);
        if (false === $result) {
            $this->checkGoneAway();
            $result = parent::exec($query);
        }

        return $result;
    }
}
