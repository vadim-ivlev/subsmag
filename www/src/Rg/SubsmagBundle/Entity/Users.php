<?php

namespace Rg\SubsmagBundle\Entity;

/**
 * Users
 */
class Users
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $userKey;

    /**
     * @var \DateTime
     */
    private $dateRegistration;

    /**
     * @var \DateTime
     */
    private $dateLastlogin;

    /**
     * @var bool
     */
    private $flagCanRest;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return Users
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set userKey
     *
     * @param string $userKey
     *
     * @return Users
     */
    public function setUserKey($userKey)
    {
        $this->userKey = $userKey;

        return $this;
    }

    /**
     * Get userKey
     *
     * @return string
     */
    public function getUserKey()
    {
        return $this->userKey;
    }

    /**
     * Set dateRegistration
     *
     * @param \DateTime $dateRegistration
     *
     * @return Users
     */
    public function setDateRegistration($dateRegistration)
    {
        $this->dateRegistration = $dateRegistration;

        return $this;
    }

    /**
     * Get dateRegistration
     *
     * @return \DateTime
     */
    public function getDateRegistration()
    {
        return $this->dateRegistration;
    }

    /**
     * Set dateLastlogin
     *
     * @param \DateTime $dateLastlogin
     *
     * @return Users
     */
    public function setDateLastlogin($dateLastlogin)
    {
        $this->dateLastlogin = $dateLastlogin;

        return $this;
    }

    /**
     * Get dateLastlogin
     *
     * @return \DateTime
     */
    public function getDateLastlogin()
    {
        return $this->dateLastlogin;
    }

    /**
     * Set flagCanRest
     *
     * @param boolean $flagCanRest
     *
     * @return Users
     */
    public function setFlagCanRest($flagCanRest)
    {
        $this->flagCanRest = $flagCanRest;

        return $this;
    }

    /**
     * Get flagCanRest
     *
     * @return bool
     */
    public function getFlagCanRest()
    {
        return $this->flagCanRest;
    }
}

