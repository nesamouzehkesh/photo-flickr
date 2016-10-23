<?php

namespace UserBundle\Service;

use AppBundle\Service\AppService;
use UserBundle\Entity\User;
use UserBundle\Entity\Role;

class UserService
{
    /**
     * @var AppService $appService
     */
    protected $appService;
    
    /**
     * 
     * @param AppService $appService
     */
    public function __construct(AppService $appService) 
    {
        $this->appService = $appService;
    }
    
    /**
     * Get a user based on $userId or create a new one if $userId is null
     * 
     * In a good practice development we should not put these kind of functions 
     * in controller, we can create a user service to performance these kind of 
     * actions
     * 
     * @param type $userId
     * @return User
     * @throws NotFoundHttpException
     */
    public function getUser($userId = null, $isAdmin = false)
    {
        // If $userId is null it means that we want to create a new user object.
        // otherwise we find a user in DB based on this $userId
        if (null === $userId) {
            
            $user = new User;
            $role = $this->getRole($isAdmin? Role::ROLE_ADMIN : Role::ROLE_USER);
            $user->addRole($role);
            
            return $user;
        }
        
        // Get Doctrine Entity Manager
        $em = $this->appService->getEntityManager();

        // Get User repository
        $user = $em->getRepository('UserBundle:User')->find($userId);

        // Check if $user is found
        if (!$user instanceof User) {
            throw new \Exception('No user was found for id ' . $userId);
        }
        
        // Return user object
        return $user;
    }
    
    /**
     * Get all users
     * 
     * @return type
     */
    public function getUsers()
    {
        // Get Doctrine Entity Manager
        $em = $this->appService->getEntityManager();
        
        return $em->getRepository('UserBundle:User')->getUsers();
    }
    
    /**
     * 
     * @param type $roleId
     * @return Role
     * @throws \Exception
     */
    public function getRole($roleId)
    {
        $em = $this->appService->getEntityManager();
        $role = $em->getRepository('UserBundle:Role')->findOneBy(array('role' => $roleId));

        // Check if $user is found
        if (!$role instanceof Role) {
            throw new \Exception('No role was found for id ' . $roleId);
        }
        
        // Return user object
        return $role;
    }  
}