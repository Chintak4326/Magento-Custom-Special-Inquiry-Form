<?php

namespace ChintakExtensions\Special\Model;

use ChintakExtensions\Special\Api\Data\SpecialInterface;

class Special extends \Magento\Framework\Model\AbstractModel implements SpecialInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'chintakextensions_special';

    /**
     * @var string
     */
    protected $_cacheTag = 'chintakextensions_special';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'chintakextensions_special';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('ChintakExtensions\Special\Model\ResourceModel\Special');
    }
    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }


    public function getFname()
    {
        return $this->getData(self::FNAME);
    }

 
    public function setFname($fname)
    {
        return $this->setData(self::FNAME, $fname);
    }


    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

 
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

   

    public function getMobile()
    {
        return $this->getData(self::MOBILE);
    }

 
    public function setMobile($mobile)
    {
        return $this->setData(self::MOBILE, $mobile);
    }


    public function getSubject()
    {
        return $this->getData(self::SUBJECT);
    }

 
    public function setSubject($subject)
    {
        return $this->setData(self::SUBJECT, $subject);
    }

   
    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

 
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }
    

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

   
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

   
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

}