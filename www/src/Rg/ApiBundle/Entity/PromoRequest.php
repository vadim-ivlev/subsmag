<?php

namespace Rg\ApiBundle\Entity;

/**
 * PromoRequest
 */
class PromoRequest
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $image;

    /**
     * @var boolean
     */
    private $is_replied;

    /**
     * @var \Rg\ApiBundle\Entity\Pin
     */
    private $pin;


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
     * Set email
     *
     * @param string $email
     *
     * @return PromoRequest
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return PromoRequest
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set isReplied
     *
     * @param boolean $isReplied
     *
     * @return PromoRequest
     */
    public function setIsReplied($isReplied)
    {
        $this->is_replied = $isReplied;

        return $this;
    }

    /**
     * Get isReplied
     *
     * @return boolean
     */
    public function getIsReplied()
    {
        return $this->is_replied;
    }

    /**
     * Set pin
     *
     * @param \Rg\ApiBundle\Entity\Pin $pin
     *
     * @return PromoRequest
     */
    public function setPin(\Rg\ApiBundle\Entity\Pin $pin = null)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin
     *
     * @return \Rg\ApiBundle\Entity\Pin
     */
    public function getPin()
    {
        return $this->pin;
    }
    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $answered;


    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return PromoRequest
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set answered
     *
     * @param \DateTime $answered
     *
     * @return PromoRequest
     */
    public function setAnswered($answered)
    {
        $this->answered = $answered;

        return $this;
    }

    /**
     * Get answered
     *
     * @return \DateTime
     */
    public function getAnswered()
    {
        return $this->answered;
    }
    /**
     * @var string
     */
    private $mime;


    /**
     * Set mime
     *
     * @param string $mime
     *
     * @return PromoRequest
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Get mime
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }
}
