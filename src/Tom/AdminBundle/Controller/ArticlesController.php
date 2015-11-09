<?php

namespace Tom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Tom\SiteBundle\Entity\Article;
use Tom\AdminBundle\Form\Type\ArticleType;

/**
 * @Route("/artykuly")
 */
class ArticlesController extends Controller
{
   /**
    * @Route(
    *       "/",
    *       name="tom_admin_articles"
    * )
    * 
    * @Template()
    */
    public function ArticlesAction()
    {
        $RepoArticles = $this->getDoctrine()->getRepository('TomSiteBundle:Article');
        $Articles = $RepoArticles->findAll();
        
        return array(
            'articles' => $Articles,
            'pageTitle' => 'Artykuły <small>lista wpisów</small>'
        );
    }
    
    /**
     * @Route(
     *      "/form/{id}", 
     *      name="tom_admin_article_form",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=NULL}
     * )
     * 
     * @Template()
     */
    public function formAction(Request $Request, $id = NULL) {

        
        if(null == $id){
            $Article = new Article();
            $Article->setAuthor($this->getUser());
            $newArticleForm = TRUE;
        }else{
            $Article = $this->getDoctrine()->getRepository('TomSiteBundle:Article')->find($id);
        }
        
        $form = $this->createForm(new ArticleType(), $Article);
        
        $form->handleRequest($Request);
        if($form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($Article);
            $em->flush();

            $message = (isset($newArticleForm)) ? 'Poprawnie dodano nowy artykuł.': 'Artykuł został‚ poprawiony.';
            $this->get('session')->getFlashBag()->add('success', $message);

            return $this->redirect($this->generateUrl('tom_admin_article_form', array(
                'id' => $Article->getId()
            )));
        }
        
        return array(
            'currPage' => 'articles',
            'form' => $form->createView(),
            'article' => $Article,
            'pageTitle' => ($newArticleForm == TRUE ? 'Artykuł <small>utwórz nowy</small>' : 'Artykuł <small>edytuj:</small>')
        );
    }
}
