<?php

namespace WelcomeBundle\Orm\Test\Entity;

class Test extends \FastD\Database\Orm\Entity
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var mixed
     */
    protected $trueName;

    /**
     * @var mixed
     */
    protected $telNumber;

    /**
     * @var mixed
     */
    protected $username;

    /**
     * @var mixed
     */
    protected $password;

    /**
     * @var mixed
     */
    protected $email;

    /**
     * @const mixed
     */
    const PRIMARY = \WelcomeBundle\Orm\Test\Field\Test::PRIMARY;

    /**
     * @const mixed
     */
    const FIELDS = \WelcomeBundle\Orm\Test\Field\Test::FIELDS;

    /**
     * @const mixed
     */
    const ALIAS = \WelcomeBundle\Orm\Test\Field\Test::ALIAS;

    /**
     * @const mixed
     */
    const TABLE = \WelcomeBundle\Orm\Test\Field\Test::TABLE;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrueName()
    {
        return $this->trueName;
    }

    /**
     * @param mixed $trueName
     * @return $this
     */
    public function setTrueName($trueName)
    {
        $this->trueName = $trueName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTelNumber()
    {
        return $this->telNumber;
    }

    /**
     * @param mixed $telNumber
     * @return $this
     */
    public function setTelNumber($telNumber)
    {
        $this->telNumber = $telNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
}