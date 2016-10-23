<?php

namespace AppBundle\Library\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * 
     * @return \AppBundle\Service\AppService
     */
    public function getAppService()
    {
        return $this->get('app.service');
    }
}