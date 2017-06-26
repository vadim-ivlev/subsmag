<?php

namespace Rg\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Rg\ApiBundle\Entity\Users
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class Users
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned": true, "comment" : "ID пользователя"})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="login", type="string", length=128, options={"default":""}, unique=true)
     */
    private $login;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, options={"default":""})
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(name="user_key", type="string", length=255, options={"default":""})
     */
    private $userKey;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_registration", type="date")
     */
    private $dateRegistration;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_lastlogin", type="date")
     */
    private $dateLastlogin;

    /**
     * @var bool
     * @ORM\Column(name="date_lastlogin", type="boolean")
     */
    private $flagCanRest;



    public function __construct() {
        $this->dateRegistration = new DateTime();
    }



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

