<?php

namespace Tom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/ustawienia")
 */
class SettingsController extends Controller
{
    
   /**
    * @Route(
    *       "/",
    *       name="tom_admin_settings"
    * )
    * 
    * @Template()
    */
    public function SettingsAction()
    {
        return array(
            'pageTitle' => 'Ustawienia witryny <small>skrót konfiguracji</small>'
        );
    }
    
   /**
    * @Route(
    *       "/seo",
    *       name="tom_admin_seo"
    * )
    * 
    * @Template("TomAdminBundle:Settings/Seo:Seo.html.twig")
    */
    public function SeoAction()
    {
        return array(
            'pageTitle' => 'SEO <small>ustawienia witryny</small>'
        );
    }
}
