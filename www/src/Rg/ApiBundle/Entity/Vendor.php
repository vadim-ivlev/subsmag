<?php

namespace Rg\ApiBundle\Entity;

/**
 * Vendor
 */
class Vendor
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $keyword;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $website;

    /**
     * @var string
     */
    private $inn;

    /**
     * @var string
     */
    private $kpp;

    /**
     * @var string
     */
    private $account;

    /**
     * @var string
     */
    private $bank;

    /**
     * @var string
     */
    private $bik;

    /**
     * @var string
     */
    private $corr;

    /**
     * @var string
     */
    private $head_name;

    /**
     * @var string
     */
    private $accountant_title;

    /**
     * @var string
     */
    private $accountant_name;

    /**
     * @var string
     */
    private $stamp_img;

    /**
     * @var string
     */
    private $head_sign_img;

    /**
     * @var string
     */
    private $accountant_sign_img;


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
     * Set name
     *
     * @param string $name
     *
     * @return Vendor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     *
     * @return Vendor
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Vendor
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Vendor
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Vendor
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Vendor
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
     * Set website
     *
     * @param string $website
     *
     * @return Vendor
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set inn
     *
     * @param string $inn
     *
     * @return Vendor
     */
    public function setInn($inn)
    {
        $this->inn = $inn;

        return $this;
    }

    /**
     * Get inn
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Set kpp
     *
     * @param string $kpp
     *
     * @return Vendor
     */
    public function setKpp($kpp)
    {
        $this->kpp = $kpp;

        return $this;
    }

    /**
     * Get kpp
     *
     * @return string
     */
    public function getKpp()
    {
        return $this->kpp;
    }

    /**
     * Set account
     *
     * @param string $account
     *
     * @return Vendor
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set bank
     *
     * @param string $bank
     *
     * @return Vendor
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank
     *
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set bik
     *
     * @param string $bik
     *
     * @return Vendor
     */
    public function setBik($bik)
    {
        $this->bik = $bik;

        return $this;
    }

    /**
     * Get bik
     *
     * @return string
     */
    public function getBik()
    {
        return $this->bik;
    }

    /**
     * Set corr
     *
     * @param string $corr
     *
     * @return Vendor
     */
    public function setCorr($corr)
    {
        $this->corr = $corr;

        return $this;
    }

    /**
     * Get corr
     *
     * @return string
     */
    public function getCorr()
    {
        return $this->corr;
    }

    /**
     * Set headName
     *
     * @param string $headName
     *
     * @return Vendor
     */
    public function setHeadName($headName)
    {
        $this->head_name = $headName;

        return $this;
    }

    /**
     * Get headName
     *
     * @return string
     */
    public function getHeadName()
    {
        return $this->head_name;
    }

    /**
     * Set accountantTitle
     *
     * @param string $accountantTitle
     *
     * @return Vendor
     */
    public function setAccountantTitle($accountantTitle)
    {
        $this->accountant_title = $accountantTitle;

        return $this;
    }

    /**
     * Get accountantTitle
     *
     * @return string
     */
    public function getAccountantTitle()
    {
        return $this->accountant_title;
    }

    /**
     * Set accountantName
     *
     * @param string $accountantName
     *
     * @return Vendor
     */
    public function setAccountantName($accountantName)
    {
        $this->accountant_name = $accountantName;

        return $this;
    }

    /**
     * Get accountantName
     *
     * @return string
     */
    public function getAccountantName()
    {
        return $this->accountant_name;
    }

    /**
     * Set stampImg
     *
     * @param string $stampImg
     *
     * @return Vendor
     */
    public function setStampImg($stampImg)
    {
        $this->stamp_img = $stampImg;

        return $this;
    }

    /**
     * Get stampImg
     *
     * @return string
     */
    public function getStampImg()
    {
        return $this->stamp_img;
    }

    /**
     * Set headSignImg
     *
     * @param string $headSignImg
     *
     * @return Vendor
     */
    public function setHeadSignImg($headSignImg)
    {
        $this->head_sign_img = $headSignImg;

        return $this;
    }

    /**
     * Get headSignImg
     *
     * @return string
     */
    public function getHeadSignImg()
    {
        return $this->head_sign_img;
    }

    /**
     * Set accountantSignImg
     *
     * @param string $accountantSignImg
     *
     * @return Vendor
     */
    public function setAccountantSignImg($accountantSignImg)
    {
        $this->accountant_sign_img = $accountantSignImg;

        return $this;
    }

    /**
     * Get accountantSignImg
     *
     * @return string
     */
    public function getAccountantSignImg()
    {
        return $this->accountant_sign_img;
    }
}

