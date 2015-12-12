<?php

namespace Tom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Tom\SiteBundle\Entity\Menu;
use Tom\AdminBundle\Form\Type\MenuType;

class MenuController extends Controller
{
    
    private $deleteTokenName = 'delete-menu-%d';
    
    /**
     * @Route(
     *      "/form/{id}", 
     *      name="tom_admin_menu_form",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=NULL}
     * )
     * 
     * @Template()
     */
    public function formAction(Request $Request, Menu $Menu = NULL) {

        if(null == $Menu){
            $Menu = new Menu();
            $newMenuForm = TRUE;
        }
        
        $form = $this->createForm(new MenuType(), $Menu);
        
        $form->handleRequest($Request);
        if($form->isValid()){
        
            $Params = array();
            
            $RoutePrameters = $Request->get('parameters');
            foreach($RoutePrameters as $param) {
                if(!empty($param['type']) && !empty($param['value'])) {
                    $Params[$param['type']] = $param['value'];
                }
            }
            
            $Menu->setRouteParameters($Params);
            $em = $this->getDoctrine()->getManager();
            $em->persist($Menu);
            $em->flush();

            $message = (isset($newMenuForm)) ? 'Poprawnie dodano nową pozycję menu.': 'Pozycja menu została zaktualizowana.';
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('tom_admin_menu_form', array(
                'id' => $Menu->getId()
            )));
        }
        
        return array(
            'pageTitle' => (isset($newMenuForm) ? 'Pozycja menu <small>nowa</small>' : 'Pozycje menu <small>edycja</small>'),
            'currPage' => 'menu',
            'form' => $form->createView(),
            'menu' => $Menu,
        );
    }
    
    /**
     * @Route(
     *      "/{status}/{page}", 
     *       name="tom_admin_menu",
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
            'typeId' => $Request->query->get('typeId'),
            'status' => $status
        );
        
        $RepoMenu = $this->getDoctrine()->getRepository('TomSiteBundle:Menu');
        
        $statistics = $RepoMenu->getStatistics();
        
        $qb = $RepoMenu->getQueryBuilder($queryParams);
        
        $paginationLimit = $this->container->getParameter('admin.pagination_limit');
        $limits = array(2, 5, 10, 15);
        
        $limit = $Request->query->get('limit', $paginationLimit);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
       
        $typesList = $this->getDoctrine()->getRepository('TomSiteBundle:MenuType')->getAsArray();
        
        $statusesList = array(
            'Opublikowane' => 'published',
            'Nieopublikowane' => 'unpublished',
            'Wszystkie' => 'all'
        );
        
        return array(
            'pageTitle' => 'Pozycje menu <small>lista</small>',
            'currPage' => 'menu',
            'queryParams' => $queryParams,
            'typesList' => $typesList,
            
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
     *      name="tom_admin_menu_delete",
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
            
            $Menu = $this->getDoctrine()->getRepository('TomSiteBundle:Menu')->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($Menu);
            $em->flush();
            
            $this->addFlash('success', 'Poprawnie usunięto pozycję menu.');
        }
        
        return $this->redirect($this->generateUrl('tom_admin_menu'));
    }
}
