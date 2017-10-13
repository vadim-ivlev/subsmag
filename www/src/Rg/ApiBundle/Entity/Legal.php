<?php

namespace Rg\ApiBundle\Entity;

/**
 * Legal
 */
class Legal
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
    private $inn;

    /**
     * @var string
     */
    private $kpp;

    /**
     * @var string
     */
    private $bank_name;

    /**
     * @var string
     */
    private $bank_account;

    /**
     * @var string
     */
    private $bik;

    /**
     * @var string
     */
    private $postcode;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $building_number;

    /**
     * @var string
     */
    private $building_subnumber;

    /**
     * @var string
     */
    private $building_part;

    /**
     * @var string
     */
    private $appartment;

    /**
     * @var string
     */
    private $contact_name;

    /**
     * @var string
     */
    private $contact_phone;

    /**
     * @var string
     */
    private $contact_email;

    /**
     * @var string
     */
    private $delivery_postcode;

    /**
     * @var string
     */
    private $delivery_street;

    /**
     * @var string
     */
    private $delivery_building_number;

    /**
     * @var string
     */
    private $delivery_building_subnumber;

    /**
     * @var string
     */
    private $delivery_building_part;

    /**
     * @var string
     */
    private $delivery_appartment;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $orders;

    /**
     * @var \Rg\ApiBundle\Entity\City
     */
    private $city;

    /**
     * @var \Rg\ApiBundle\Entity\City
     */
    private $delivery_city;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Legal
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
     * Set inn
     *
     * @param string $inn
     *
     * @return Legal
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
     * @return Legal
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
     * Set bankName
     *
     * @param string $bankName
     *
     * @return Legal
     */
    public function setBankName($bankName)
    {
        $this->bank_name = $bankName;

        return $this;
    }

    /**
     * Get bankName
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bank_name;
    }

    /**
     * Set bankAccount
     *
     * @param string $bankAccount
     *
     * @return Legal
     */
    public function setBankAccount($bankAccount)
    {
        $this->bank_account = $bankAccount;

        return $this;
    }

    /**
     * Get bankAccount
     *
     * @return string
     */
    public function getBankAccount()
    {
        return $this->bank_account;
    }

    /**
     * Set bik
     *
     * @param string $bik
     *
     * @return Legal
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
     * Set postcode
     *
     * @param string $postcode
     *
     * @return Legal
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Legal
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set buildingNumber
     *
     * @param string $buildingNumber
     *
     * @return Legal
     */
    public function setBuildingNumber($buildingNumber)
    {
        $this->building_number = $buildingNumber;

        return $this;
    }

    /**
     * Get buildingNumber
     *
     * @return string
     */
    public function getBuildingNumber()
    {
        return $this->building_number;
    }

    /**
     * Set buildingSubnumber
     *
     * @param string $buildingSubnumber
     *
     * @return Legal
     */
    public function setBuildingSubnumber($buildingSubnumber)
    {
        $this->building_subnumber = $buildingSubnumber;

        return $this;
    }

    /**
     * Get buildingSubnumber
     *
     * @return string
     */
    public function getBuildingSubnumber()
    {
        return $this->building_subnumber;
    }

    /**
     * Set buildingPart
     *
     * @param string $buildingPart
     *
     * @return Legal
     */
    public function setBuildingPart($buildingPart)
    {
        $this->building_part = $buildingPart;

        return $this;
    }

    /**
     * Get buildingPart
     *
     * @return string
     */
    public function getBuildingPart()
    {
        return $this->building_part;
    }

    /**
     * Set appartment
     *
     * @param string $appartment
     *
     * @return Legal
     */
    public function setAppartment($appartment)
    {
        $this->appartment = $appartment;

        return $this;
    }

    /**
     * Get appartment
     *
     * @return string
     */
    public function getAppartment()
    {
        return $this->appartment;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     *
     * @return Legal
     */
    public function setContactName($contactName)
    {
        $this->contact_name = $contactName;

        return $this;
    }

    /**
     * Get contactName
     *
     * @return string
     */
    public function getContactName()
    {
        return $this->contact_name;
    }

    /**
     * Set contactPhone
     *
     * @param string $contactPhone
     *
     * @return Legal
     */
    public function setContactPhone($contactPhone)
    {
        $this->contact_phone = $contactPhone;

        return $this;
    }

    /**
     * Get contactPhone
     *
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contact_phone;
    }

    /**
     * Set contactEmail
     *
     * @param string $contactEmail
     *
     * @return Legal
     */
    public function setContactEmail($contactEmail)
    {
        $this->contact_email = $contactEmail;

        return $this;
    }

    /**
     * Get contactEmail
     *
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contact_email;
    }

    /**
     * Set deliveryPostcode
     *
     * @param string $deliveryPostcode
     *
     * @return Legal
     */
    public function setDeliveryPostcode($deliveryPostcode)
    {
        $this->delivery_postcode = $deliveryPostcode;

        return $this;
    }

    /**
     * Get deliveryPostcode
     *
     * @return string
     */
    public function getDeliveryPostcode()
    {
        return $this->delivery_postcode;
    }

    /**
     * Set deliveryStreet
     *
     * @param string $deliveryStreet
     *
     * @return Legal
     */
    public function setDeliveryStreet($deliveryStreet)
    {
        $this->delivery_street = $deliveryStreet;

        return $this;
    }

    /**
     * Get deliveryStreet
     *
     * @return string
     */
    public function getDeliveryStreet()
    {
        return $this->delivery_street;
    }

    /**
     * Set deliveryBuildingNumber
     *
     * @param string $deliveryBuildingNumber
     *
     * @return Legal
     */
    public function setDeliveryBuildingNumber($deliveryBuildingNumber)
    {
        $this->delivery_building_number = $deliveryBuildingNumber;

        return $this;
    }

    /**
     * Get deliveryBuildingNumber
     *
     * @return string
     */
    public function getDeliveryBuildingNumber()
    {
        return $this->delivery_building_number;
    }

    /**
     * Set deliveryBuildingSubnumber
     *
     * @param string $deliveryBuildingSubnumber
     *
     * @return Legal
     */
    public function setDeliveryBuildingSubnumber($deliveryBuildingSubnumber)
    {
        $this->delivery_building_subnumber = $deliveryBuildingSubnumber;

        return $this;
    }

    /**
     * Get deliveryBuildingSubnumber
     *
     * @return string
     */
    public function getDeliveryBuildingSubnumber()
    {
        return $this->delivery_building_subnumber;
    }

    /**
     * Set deliveryBuildingPart
     *
     * @param string $deliveryBuildingPart
     *
     * @return Legal
     */
    public function setDeliveryBuildingPart($deliveryBuildingPart)
    {
        $this->delivery_building_part = $deliveryBuildingPart;

        return $this;
    }

    /**
     * Get deliveryBuildingPart
     *
     * @return string
     */
    public function getDeliveryBuildingPart()
    {
        return $this->delivery_building_part;
    }

    /**
     * Set deliveryAppartment
     *
     * @param string $deliveryAppartment
     *
     * @return Legal
     */
    public function setDeliveryAppartment($deliveryAppartment)
    {
        $this->delivery_appartment = $deliveryAppartment;

        return $this;
    }

    /**
     * Get deliveryAppartment
     *
     * @return string
     */
    public function getDeliveryAppartment()
    {
        return $this->delivery_appartment;
    }

    /**
     * Add order
     *
     * @param \Rg\ApiBundle\Entity\Order $order
     *
     * @return Legal
     */
    public function addOrder(\Rg\ApiBundle\Entity\Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \Rg\ApiBundle\Entity\Order $order
     */
    public function removeOrder(\Rg\ApiBundle\Entity\Order $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set city
     *
     * @param \Rg\ApiBundle\Entity\City $city
     *
     * @return Legal
     */
    public function setCity(\Rg\ApiBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Rg\ApiBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set deliveryCity
     *
     * @param \Rg\ApiBundle\Entity\City $deliveryCity
     *
     * @return Legal
     */
    public function setDeliveryCity(\Rg\ApiBundle\Entity\City $deliveryCity = null)
    {
        $this->delivery_city = $deliveryCity;

        return $this;
    }

    /**
     * Get deliveryCity
     *
     * @return \Rg\ApiBundle\Entity\City
     */
    public function getDeliveryCity()
    {
        return $this->delivery_city;
    }
    /**
     * @var string
     */
    private $bank_corr_account;


    /**
     * Set bankCorrAccount
     *
     * @param string $bankCorrAccount
     *
     * @return Legal
     */
    public function setBankCorrAccount($bankCorrAccount)
    {
        $this->bank_corr_account = $bankCorrAccount;

        return $this;
    }

    /**
     * Get bankCorrAccount
     *
     * @return string
     */
    public function getBankCorrAccount()
    {
        return $this->bank_corr_account;
    }
    /**
     * @var string
     */
    private $contact_fax;


    /**
     * Set contactFax
     *
     * @param string $contactFax
     *
     * @return Legal
     */
    public function setContactFax($contactFax)
    {
        $this->contact_fax = $contactFax;

        return $this;
    }

    /**
     * Get contactFax
     *
     * @return string
     */
    public function getContactFax()
    {
        return $this->contact_fax;
    }
}
