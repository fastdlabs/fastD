<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Model;

use Adinf\RagnarSDK\RagnarConst;
use Adinf\RagnarSDK\RagnarSDK;
use Exception;
use Medoo\Medoo;
use PDO;

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
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
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
     *
     * @return bool|\PDOStatement
     */
    public function query($query)
    {
        $start = RagnarSDK::digLogStart(__FILE__, __LINE__, "mysql");

        try {
            $result = parent::query($query);
        } catch (Exception $e) {
            $this->reconnect();

            $result = parent::query($query);
        }
        RagnarSDK::digLogEnd($start, array(
            "sql" => $query,
            "data" => "sql的参数",
            "op" => "select\delete\update\...",
            "fun" => "execute_sql",
        ));

        return $result;
    }

    /**
     * @param $query
     *
     * @return bool|\PDOStatement
     */
    public function exec($query)
    {
        $start = RagnarSDK::digLogStart(__FILE__, __LINE__, "mysql");

        try {
            $result = parent::exec($query);
        } catch (Exception $e) {
            $this->reconnect();

            $result =parent::exec($query);
        }

        RagnarSDK::digLogEnd($start, array(
            "sql" => $query,
            "data" => "sql的参数",
            "op" => "select\delete\update\...",
            "fun" => "execute_sql",
        ));

        return $result;
    }

    public function has($table, $join, $where = null)
    {
        $column = null;

        $query = $this->query('SELECT EXISTS('.$this->selectContext($table, $join, $column, $where, 1).')');

        if ($query && intval($query->fetchColumn()) === 1) {
            return true;
        }

        return false;
    }
}
