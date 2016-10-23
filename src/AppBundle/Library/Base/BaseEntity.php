<?php

namespace AppBundle\Library\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class BaseEntity
{
    /**
     * @ORM\Column(name="created_time", type="integer", nullable=true)
     */
    protected $createdTime;
    
    /**
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true, options={"default"= 0})
     */
    protected $deleted;

    /**
     * @ORM\Column(name="deleted_time", type="bigint", nullable=true)
     */
    protected $deletedTime;
    
    /**
     * 
     */
    public function __construct()
    {
        $date = new \DateTime();
        $this->deleted = 0;
        $this->createdTime = $date->getTimestamp();
    }
    
    /**
     * 
     * @return type
     */
    public function isNew()
    {
        return null === $this->getId();
    }
    
    /**
     * Set modified time
     *
     * @param integer $modifiedTime
     * @return Page
     */
    public function setModifiedTime($modifiedTime = null)
    {
        if (null === $modifiedTime) {
            $date = new \DateTime();
            $modifiedTime = $date->getTimestamp();
        }
        $this->modifiedTime = $modifiedTime;

        return $this;
    }

    /**
     * Get modified time
     *
     * @return integer 
     */
    public function getModifiedTime()
    {
        return $this->modifiedTime;
    }    

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return Rating
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    
        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function isDeleted()
    {
    	return $this->deleted;
    }

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
    
    /**
     * Set deleted time as current time
     * 
     * @return \Library\BaseEntity
     */
    public function setDeletedTime()
    {
        $this->deletedTime = time();
        
        return $this;
    }
    
    /**
     * Get deletedTime
     * @return integer
     */
    public function getDeletedTime()
    {
        return $this->deletedTime;
    }
    
    /**
     * Get class name
     * 
     * @return type
     */
    public static function getClass()
    {
         return get_called_class();
    }    
}