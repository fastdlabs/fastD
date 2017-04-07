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
 * Class Model.
 */
class Model
{
    /**
     * @var medoo
     */
    protected $db;

    /**
     * Model constructor.
     *
     * @param medoo $medoo
     */
    public function __construct(Medoo $medoo)
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
