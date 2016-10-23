<?php

namespace FlikerBundle\Service;

use AppBundle\Service\AppService;
use FlikerBundle\Entity\Category;

class FlikerService
{
    /**
     *
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
     * Get Item based on its ID. If ID is null then create a new Item
     * 
     * @param int $categoryId
     * @return type
     */
    public function getCategory($categoryId = null)
    {
        if (null === $categoryId) {
            return $this->makeCategory();
        }
        
        // Get Category form repository
        $category = $this->appService->getEntityManager()
            ->getRepository('FlikerBundle:Category')
            ->getCategory($categoryId);

        // Check if category is found
        if (!$category instanceof Category) {
            throw $this->appService
                ->getAppException('alert.error.noItemFound');
        }

        return $category;
    }
    
    /**
     * 
     * @param type $justQuery
     * @return type
     */
    public function getCategories($justQuery = true)
    {
        return $this->appService->getEntityManager()
            ->getRepository('FlikerBundle:Category')
            ->getCategories($justQuery);
    }
    
    /**
     * 
     * @return Category
     */
    public function makeCategory()
    {
        return new Category();
    }
}