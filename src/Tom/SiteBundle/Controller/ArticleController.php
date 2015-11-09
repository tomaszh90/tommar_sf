<?php

namespace Tom\SiteBundle\Controller;

use Tom\SiteBundle\Entity\Seo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/artykul")
 */
class ArticleController extends Controller
{
    
   /**
    * @Route(
    *       "/",
    *       name="tom_site_article"
    * )
    * 
    * @Template()
    */
    public function ArticleAction()
    {
//        $id=1;
//        $em = $this->getDoctrine() ->getManager();
//        $seo = $em->getRepository('TomSiteBundle:Seo')->find($id);
//        
//        return array (
//            'seo' => $seo
//         );
        
       return array();
    }
}
