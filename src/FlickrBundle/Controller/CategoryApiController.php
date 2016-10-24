<?php

namespace FlickrBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Library\Base\BaseController;

class CategoryApiController extends BaseController
{
    /**
     * @Route("/api/flickr/categories", name="api_flickr_category_index")
     */
    public function getCategoriesAction(Request $request)
    {
        // Search criteria
        // Get all query parameters
        $criteria = $request->query->all();
        
        // Get a query of listing all categories from category service
        $categories = $this
            ->get('app.flickr.service')
            ->getCategoriesData($criteria);
        
        return $this->get('app.service')
            ->getJsonResponse(array('categories' => $categories));
    }
}