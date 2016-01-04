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
class SugestionController extends Controller {
    /**
     * @Route(
     *       "/",
     *       name="tom_site_sugestion",
     * )
     * 
     * @Template()
     */
    public function indexAction(Request $Request) {

        return array(
            'pageTitle' => 'Aktualności'
        );
    }
}
