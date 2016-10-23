<?php

namespace AppBundle\Service;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class Md5Encoder implements PasswordEncoderInterface
{
    /**
     * 
     * @param type $raw
     * @param type $salt
     * @return type
     */
    public function encodePassword($raw, $salt)
    {
        // we use one global salt at the moment, so ignore $salt
        //return md5($raw.$this->saltmain);
        return md5($raw);
    }
    
    /**
     * 
     * @param type $encoded
     * @param type $raw
     * @param type $salt
     * @return type
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $encoded === $this->encodePassword($raw, $salt);
    }
}