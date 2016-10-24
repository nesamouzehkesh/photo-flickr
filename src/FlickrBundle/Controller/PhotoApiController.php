<?php

namespace FlickrBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Library\Base\BaseController;

class PhotoApiController extends BaseController
{
    /**
     * 
     * @Route("/api/flickr/photos", name="api_flickr_search_photos")
     */
    public function searchPhotos(Request $request)
    {
        // Search criteria
        $criteria = $request->query->all();
        
        // Get all photos based on this $criteria
        $photos = $this->get('app.flickr.service')->searchPhotos($criteria);
        
        // Generate JSON responce
        return $this->get('app.service')
            ->getJsonResponse(array('photos' => $photos));
    }
    
    /**
     * 
     * @Route("/api/flickr/photos/{id}", name="api_flickr_get_photo")
     */
    public function getPhoto($id)
    {
        // Get all photos based on this $criteria
        $photo = $this->get('app.flickr.service')->getPhoto($id);
        
        // Generate JSON responce
        return $this->get('app.service')
            ->getJsonResponse(array('photo' => $photo));
    }    
}