<?php

namespace Welcome\Orm\Entity;

use FastD\Database\ORM\Entity;

class Test extends Entity
{
    /**
     * @const string
     */
    const PRIMARY = \Welcome\Orm\Fields\TestFields::PRIMARY;

    /**
     * Fields const
     * @const array
     */
    const FIELDS = \Welcome\Orm\Fields\TestFields::FIELDS;

    /**
     * Fields alias
     * @const array
     */
    const ALIAS = \Welcome\Orm\Fields\TestFields::ALIAS;

    /**
     * @var string
     */
    protected $table = 'test';

    /**
     * @var string|null
     */
    protected $repository = 'Welcome\Orm\Repository\TestRepository';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $trueName;

    /**
     * @var string
     */
    protected $tel;
    /**
     * getId
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setId
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * getTrueName
     *
     * @return string
     */
    public function getTrueName()
    {
        return $this->trueName;
    }

    /**
     * setTrueName
     *
     * @param string $trueName
     * @return $this
     */
    public function setTrueName($trueName)
    {
        $this->trueName = $trueName;

        return $this;
    }

    /**
     * getTel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * setTel
     *
     * @param string $tel
     * @return $this
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }
}