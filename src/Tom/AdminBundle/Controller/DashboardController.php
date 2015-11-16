<?php

namespace Tom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardController extends Controller
{
   /**
    * @Route(
    *       "/",
    *       name="tom_admin_dashboard"
    * )
    * 
    * @Template()
    */
    public function indexAction()
    {
        $user = $this->getUser();

         // the above is a shortcut for this
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return array(
            'pageTitle' => 'Dashboard <small>najnowsze zdarzenia</small>',
            'user' => $user
        );
    }
}
