<?php

namespace Rg\ApiBundle\Entity;

/**
 * Content
 */
class Content
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $departments;

    /**
     * @var string
     */
    private $contacts;

    /**
     * @var string
     */
    private $subscribe;

    /**
     * @var string
     */
    private $support;

    /**
     * @var string
     */
    private $auxiliary;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set departments
     *
     * @param string $departments
     *
     * @return Content
     */
    public function setDepartments($departments)
    {
        $this->departments = $departments;

        return $this;
    }

    /**
     * Get departments
     *
     * @return string
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Set contacts
     *
     * @param string $contacts
     *
     * @return Content
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Get contacts
     *
     * @return string
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set subscribe
     *
     * @param string $subscribe
     *
     * @return Content
     */
    public function setSubscribe($subscribe)
    {
        $this->subscribe = $subscribe;

        return $this;
    }

    /**
     * Get subscribe
     *
     * @return string
     */
    public function getSubscribe()
    {
        return $this->subscribe;
    }

    /**
     * Set support
     *
     * @param string $support
     *
     * @return Content
     */
    public function setSupport($support)
    {
        $this->support = $support;

        return $this;
    }

    /**
     * Get support
     *
     * @return string
     */
    public function getSupport()
    {
        return $this->support;
    }

    /**
     * Set auxiliary
     *
     * @param string $auxiliary
     *
     * @return Content
     */
    public function setAuxiliary($auxiliary)
    {
        $this->auxiliary = $auxiliary;

        return $this;
    }

    /**
     * Get auxiliary
     *
     * @return string
     */
    public function getAuxiliary()
    {
        return $this->auxiliary;
    }
}

