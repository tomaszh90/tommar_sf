<?php

namespace Tom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Tom\SiteBundle\Entity\Page;
use Tom\AdminBundle\Form\Type\PageType;

class PagesController extends Controller
{
    
    private $deleteTokenName = 'delete-page-%d';
    
    /**
     * @Route(
     *      "/form/{id}", 
     *      name="tom_admin_page_form",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=NULL}
     * )
     * 
     * @Template()
     */
    public function formAction(Request $Request, Page $Page = NULL) {

        if(null == $Page){
            $Page = new Page();
            $newPageForm = TRUE;
        }
        
        $form = $this->createForm(new PageType(), $Page);
        
        $form->handleRequest($Request);
        if($form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($Page);
            $em->flush();

            $message = (isset($newPageForm)) ? 'Poprawnie dodano nową stronę statyczną.': 'Strona statyczna została zaktualizowana.';
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('tom_admin_page_form', array(
                'id' => $Page->getId()
            )));
        }
        
        return array(
            'pageTitle' => (isset($newPageForm) ? 'Strony statyczne <small>utwórz nową</small>' : 'Strony statyczne <small>edycja strony</small>'),
            'currPage' => 'articles',
            'form' => $form->createView(),
            'page' => $Page,
        );
    }
    
    /**
     * @Route(
     *      "/{status}/{page}", 
     *       name="tom_admin_pages",
     *      requirements={"page"="\d+"},
     *      defaults={"status"="all", "page"=1}
     * )
     * 
     * @Template()
     */
    public function indexAction(Request $Request, $status, $page)
    {
        $queryParams = array(
            'titleLike' => $Request->query->get('titleLike'),
            'status' => $status
        );
        
        $RepoPages = $this->getDoctrine()->getRepository('TomSiteBundle:Page');
        
        $statistics = $RepoPages->getStatistics();
        
        $qb = $RepoPages->getQueryBuilder($queryParams);
        
        $paginationLimit = $this->container->getParameter('admin.pagination_limit');
        $limits = array(2, 5, 10, 15);
        
        $limit = $Request->query->get('limit', $paginationLimit);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
       
        $statusesList = array(
            'Opublikowane' => 'published',
            'Nieopublikowane' => 'unpublished',
            'Wszystkie' => 'all'
        );
        
        return array(
            'pageTitle' => 'Strony statyczne <small>lista stron</small>',
            'currPage' => 'pages',
            'queryParams' => $queryParams,
            
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
     *      name="tom_admin_page_delete",
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
            
            $Page = $this->getDoctrine()->getRepository('TomSiteBundle:Page')->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($Page);
            $em->flush();
            
            $this->addFlash('success', 'Poprawnie usunięto stronę statyczną.');
        }
        
        return $this->redirect($this->generateUrl('tom_admin_pages'));
    }
}
