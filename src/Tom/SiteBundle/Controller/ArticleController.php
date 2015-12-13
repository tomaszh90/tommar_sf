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
class ArticleController extends Controller
{
    
    /**
    * @Route(
    *       "/artykuly",
    *       name="tom_site_articles"
    * )
    * 
    * @Template()
    */
    public function listAction()
    {
       return array();
    }
    
   /**
    * @Route(
    *       "/{id}-{slug}",
    *       name="tom_site_article",
    *       requirements = {"id" = "\d+"}
    * )
    * 
    * @Template()
    */
    public function indexAction($id)
    {
        $RepoArticle = $this->getDoctrine()->getRepository('TomSiteBundle:Article');
        $Article = $RepoArticle->getPublishedArticle($id);
        
        if(NULL == $Article) {
            throw $this->createNotFoundException('Nie znaleziono takiego artykułu.'); 
        }
        
       return array(
           'article' => $Article
       );
    }

}
