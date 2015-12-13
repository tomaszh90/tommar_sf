<?php

namespace Tom\SiteBundle\Controller;

use Tom\SiteBundle\Entity\Seo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/")
 */
class IndexController extends Controller {

    /**
     * @Route(
     *       "/",
     *       name="tom_site_homepage"
     * )
     * 
     * @Template()
     */
    public function indexAction() {
        $id = 1;
        $em = $this->getDoctrine()->getManager();
        $seo = $em->getRepository('TomSiteBundle:Seo')->find($id);
        $em_script = $this->getDoctrine()->getManager();
        $javascript = $em_script->getRepository('TomSiteBundle:Javascript')->find($id);

        $RepoMenu = $this->getDoctrine()->getRepository('TomSiteBundle:Menu');
        
        $params = array(
            'parentId' => NULL
        );

        $MenuPos = $RepoMenu->getMenuBuilder($params);
       

        return array(
            'seo' => $seo,
            'javascript' => $javascript,
            'menu' => $MenuPos
        );

        //return array();
    }

}
