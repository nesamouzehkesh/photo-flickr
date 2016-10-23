<?php

namespace UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use UserBundle\Entity\Role;

class RoleRepository extends EntityRepository 
{
    /**
     * 
     * @return type
     */
    public function getAdminRole()
    {
        return $this->getRole(Role::ROLE_ADMIN);
    }
    
    /**
     * 
     * @return type
     */
    public function getUserRole()
    {
        return $this->getRole(Role::ROLE_USER);
    }    
    
    /**
     * 
     * @param type $role
     * @return type
     */
    public function getRole($role)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.deleted = 0 AND r.role = :role')
            ->setParameter('role', $role);
            
        return $qb->getQuery()->getOneOrNullResult();
    }   
}