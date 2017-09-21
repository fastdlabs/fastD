<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Model;

/**
 * Class Model.
 */
class Model
{
    /**
     * @var Database
     */
    protected $db;

    /**
     * Model constructor.
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    /**
     * @return Database
     */
    public function getDatabase()
    {
        return $this->db;
    }
}
