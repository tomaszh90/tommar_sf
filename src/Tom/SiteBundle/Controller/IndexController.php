<?php

namespace Tom\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/")
 */
class IndexController extends Controller
{
    
   /**
    * @Route(
    *       "/",
    *       name="tom_site_homepage"
    * )
    * 
    * @Template()
    */
    public function indexAction()
    {
        
       return array();
    }
}
