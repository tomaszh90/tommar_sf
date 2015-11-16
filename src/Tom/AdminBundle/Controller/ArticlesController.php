<?php

namespace Tom\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Tom\SiteBundle\Entity\Article;
use Tom\AdminBundle\Form\Type\ArticleType;


class ArticlesController extends Controller
{
    private $deleteTokenName = 'delete-article-%d';
    
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
    public function formAction(Request $Request, Article $Article = NULL) {

        if(null == $Article){
            $Article = new Article();
            $Article->setAuthor($this->getUser());
            $newArticleForm = TRUE;
        }
        
        $form = $this->createForm(new ArticleType(), $Article);
        
        $form->handleRequest($Request);
        if($form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($Article);
            $em->flush();

            $message = (isset($newArticleForm)) ? 'Poprawnie dodano nowy artykuł.': 'Artykuł został zaktualizowany.';
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('tom_admin_article_form', array(
                'id' => $Article->getId()
            )));
        }
        
        return array(
            'pageTitle' => (isset($newArticleForm) ? 'Artykuł <small>utwórz nowy</small>' : 'Artykuł <small>edycja</small>'),
            'currPage' => 'articles',
            'form' => $form->createView(),
            'article' => $Article,
        );
    }
    
    /**
     * @Route(
     *      "/{status}/{page}", 
     *      name="tom_admin_articles",
     *      requirements={"page"="\d+"},
     *      defaults={"status"="all", "page"=1}
     * )
     * 
     * @Template()
     */
    public function indexAction(Request $request, $status, $page) {
        
        $queryParams = array(
            'titleLike' => $request->query->get('titleLike'),
            'categoryId' => $request->query->get('categoryId'),
            'status' => $status
        );
        
        $RepoArticle = $this->getDoctrine()->getRepository('TomSiteBundle:Article');
        
        $statistics = $RepoArticle->getStatistics();
        
        $qb = $RepoArticle->getQueryBuilder($queryParams);
        
        $paginationLimit = $this->container->getParameter('admin.pagination_limit');
        $limits = array(2, 5, 10, 15);
        
        $limit = $request->query->get('limit', $paginationLimit);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
        
        $categoriesList = $this->getDoctrine()->getRepository('TomSiteBundle:ArticleCategory')->getAsArray();
       
        $statusesList = array(
            'Opublikowane' => 'published',
            'Nieopublikowane' => 'unpublished',
            'Wszystkie' => 'all'
        );
        
        return array(
            'pageTitle' => 'Artykuły <small>lista wpisów</small>',
            'currPage' => 'articles',
            'queryParams' => $queryParams,
            'categoriesList' => $categoriesList,
            
            'limits' => $limits,
            'currLimit' => $limit,
            
            'statusesList' => $statusesList,
            'currStatus' => $status,
            'statistics' => $statistics,
            
            'pagination' => $pagination,
            'currStatus' => $status,
            
            'deleteTokenName' => $this->deleteTokenName,
            'csrfProvider' => $this->get('form.csrf_provider')
        );
    }
    
    /**
     * @Route(
     *      "/usun/{id}/{token}", 
     *      name="tom_admin_article_delete",
     *      requirements={"id"="\d+"}
     * )
     * 
     * @Template()
     */
    public function deleteAction($id, $token) {
        
        $tokenName = sprintf($this->deleteTokenName, $id);
        $csrfProvider = $this->get('form.csrf_provider');
        
        if(!$csrfProvider->isCsrfTokenValid($tokenName, $token)){
            $this->addFlash('error', 'Niepoprawny token akcji.');
            
        }else{
            
            $Article = $this->getDoctrine()->getRepository('TomSiteBundle:Article')->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($Article);
            $em->flush();
            
            $this->addFlash('success', 'Poprawnie usunięto artykuł wraz z komentarzami.');
        }
        
        return $this->redirect($this->generateUrl('tom_admin_articles'));
    }
    
}