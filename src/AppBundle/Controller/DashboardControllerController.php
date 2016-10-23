<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Library\Base\BaseController;

class DashboardControllerController extends BaseController
{
    /**
     * @Route("/", name="admin_app_dashboard")
     */    
    public function displayDashboardAction()
    {
        return $this->render(
            '::admin/app/dashboard.html.twig',
            array()
        );
    }
}