<?php

namespace FlickrBundle\Service;

use AppBundle\Service\AppService;
use FlickrBundle\Entity\Category;

class FlickrService
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
            ->getRepository('FlickrBundle:Category')
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
     * @return type
     */
    public function getCategories()
    {
        return $this->appService->getEntityManager()
            ->getRepository('FlickrBundle:Category')
            ->getCategories();
    }
    
    /**
     * 
     * @param type $criteria
     * @return type
     */
    public function getCategoriesData($criteria = array())
    {
        return $this->appService->getEntityManager()
            ->getRepository('FlickrBundle:Category')
            ->getCategoriesData($criteria);
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