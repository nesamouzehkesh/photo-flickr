<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Library\Base\BaseController;

class DashboardController extends BaseController
{
    /**
     * @Route("/admin", name="admin_app_dashboard")
     */    
    public function displayDashboardAction()
    {
        return $this->render(
            '::admin/app/dashboard.html.twig',
            array()
        );
    }
}