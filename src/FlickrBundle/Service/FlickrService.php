<?php

namespace FlickrBundle\Service;

use AppBundle\Service\AppService;
use FlickrBundle\Entity\Category;
use FlickrBundle\Library\NesaFlickrApi\NesaFlickrApi;
use FlickrBundle\Library\NesaFlickrApi\NesaFlickrPhotoRepository;
use Ideato\FlickrApi\Wrapper\Curl;

class FlickrService
{
    /**
     *
     * @var AppService $appService
     */
    protected $appService;
    
    /**
     *
     * @var array $params
     */
    protected $params;
    
    /**
     *
     * @var NesaFlickrApi 
     */
    protected $flickrApi;

    /**
     * 
     * @param AppService $appService
     */
    public function __construct(AppService $appService, $params) 
    {
        $this->appService = $appService;
        $this->params = $params;
        
        // Make a new flickr api
        $this->flickrApi = new NesaFlickrApi(
            new Curl(), 
            $this->params['apiUrl'],
            $this->params['apiUserId'],
            $this->params['apiKey']
            );        
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
    
    /**
     * Search photos
     * 
     * @param array $criteria
     * @return type
     */
    public function searchPhotos($criteria = array())
    {
        // Flickr image repository
        $repository = new NesaFlickrPhotoRepository();
        
        // Calls the flickr.photos.search api method with the given search tag
        $tag = isset($criteria['tag'])? $criteria['tag']: '';
        $xml = $this->flickrApi->searchPhotos($tag);
        
        // Conver XML result to simple array
        $photos = $repository->getPhotosFromXml($xml);
        
        return $photos;
    }
    
    /**
     * 
     * @param type $id
     */
    public function getPhoto($id)
    {
        // Flickr image repository
        $repository = new NesaFlickrPhotoRepository();
        
        // Calls the flickr.photos.search api method with the given search tag
        $xml = $this->flickrApi->getPhoto($id);
        
        // Conver XML result to simple array
        $photo = $repository->getPhotoFromXml($xml);
        
        return $photo;
    }
}