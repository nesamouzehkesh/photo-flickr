<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Library\Base\BaseController;

class FrontController extends BaseController
{
    /**
     * @Route("/", name="front_home")
     */    
    public function displayDashboardAction()
    {
        return $this->render(
            '::front/index.html.twig',
            array()
        );
    }
}