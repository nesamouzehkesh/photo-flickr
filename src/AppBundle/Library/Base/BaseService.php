<?php

namespace AppBundle\Library\Base;

abstract class BaseService 
{
    /**
     *
     * @var type 
     */
    protected $parameters = array();
    
    /**
     * Set parameters
     * 
     * @param type $parameters
     */
    public function setParametrs($parameters)
    {
        if (null !== $parameters && is_array($parameters)) {
            $this->parameters = array_merge($this->parameters, $parameters);
        }

        return $this;
    }

    /**
     * Get a parameter from $parameters array
     * 
     * @param type $key
     * @param type $default
     * @return type
     */
    public function getParameter($key, $default = null)
    {
        if (array_key_exists($key, $this->parameters)) {
            return $this->parameters[$key];
        }
        
        return $default;
    }
    
    /**
     * Get all parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }    
}