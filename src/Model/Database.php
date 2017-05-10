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
use PDO;
use PDOException;

/**
 * Class Database.
 */
class Database extends Medoo
{
    /**
     * @var array|null
     */
    protected $config = [];

    /**
     * @var PDO
     */
    public $pdo;

    /**
     * Database constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = null)
    {
        $this->config = $options;

        parent::__construct($this->config);

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * reconnect database.
     */
    public function reconnect()
    {
        $this->__construct($this->config);
    }

    /**
     * @param $query
     * @param array $map
     * @return bool|\PDOStatement
     */
    public function query($query, $map = [])
    {
        try {
            return parent::query($query, $map);
        } catch (PDOException $e) {
            if ('HY000' !== $e->getCode()) {
                throw $e;
            }
            $this->reconnect();

            return parent::query($query, $map);
        }
    }

    /**
     * @param $query
     * @param array $map
     * @return bool|\PDOStatement
     */
    public function exec($query, $map = [])
    {
        try {
            return parent::exec($query, $map);
        } catch (PDOException $e) {
            if ('HY000' !== $e->getCode()) {
                throw $e;
            }
            $this->reconnect();

            return parent::exec($query, $map);
        }
    }
}
