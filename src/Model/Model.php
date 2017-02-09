<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Model;


use medoo;

/**
 * Class Model
 * @package FastD\ServiceProvider
 */
class Model
{
    /**
     * @var medoo
     */
    protected $db;

    /**
     * Model constructor.
     * @param medoo $medoo
     */
    public function __construct(medoo $medoo)
    {
        $this->db = $medoo;
    }

    /**
     * @return medoo
     */
    public function getDatabase()
    {
        return $this->db;
    }
}