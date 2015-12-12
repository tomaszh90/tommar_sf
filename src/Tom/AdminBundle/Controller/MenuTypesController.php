<?php

namespace Tom\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Tom\SiteBundle\Entity\MenuType;
use Tom\AdminBundle\Form\Type\MenuTypeType;
use Tom\AdminBundle\Form\Type\MenuTypeDeleteType;

class MenuTypesController extends Controller {    

    /**
     * @Route(
     *      "/form/{id}", 
     *      name="tom_admin_menu_type_form",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=NULL}
     * )
     * 
     * @Template()
     */
    public function formAction(Request $Request, MenuType $Type = NULL) {
        
        if(NULL === $Type){
            $Type = new MenuType();
            $newType = TRUE;
        }
        
        $form = $this->createForm(new MenuTypeType(), $Type)   
                    ->handleRequest($Request);
        
        if($form->isValid()){
                
            $em = $this->getDoctrine()->getManager();
            $em->persist($Type);
            $em->flush();

            $message = (isset($newType)) ? 'Poprawnie dodano nowy typ menu.': 'Zaktualizowane typ menu.';
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('tom_admin_menu_type_form', array(
                'id' => $Type->getId()
            )));
        }
        
        return array(
            'pageTitle' => (isset($newType) ? 'Typy menu <small>nowy</small>' : 'Typy menu <small>edycja</small>'),
            'currPage'  => 'types',
            'form'      => $form->createView(),
            'type'  => $Type
        );
    }
    
    /**
     * @Route(
     *      "/{page}", 
     *      name="tom_admin_menu_types",
     *      requirements={"page"="\d+"},
     *      defaults={"page"=1}
     * )
     * 
     * @Template()
     */
    public function indexAction(Request $Request, $page) {
        
        $queryParams = array(
            'nameLike' => $Request->query->get('nameLike'),
        );
        
        $RepoMenuType = $this->getDoctrine()->getRepository('TomSiteBundle:MenuType');
        $qb = $RepoMenuType->getQueryBuilder();
        
        $paginationLimit = $this->container->getParameter('admin.pagination_limit');
        $limits = array(2, 5, 10, 15);
        $limit = $Request->query->get('limit', $paginationLimit);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
        
            
        return array(
            'pageTitle' => 'Typy menu <small>lista</small>',
            
            'currPage' => 'types',
            'queryParams' => $queryParams,
            
            'pagination' => $pagination,
            
            'limits' => $limits,
            'currLimit' => $limit,
        );
    }
    
    
    /**
     * @Route(
     *      "/usun/{id}", 
     *      name="tom_admin_menu_type_delete"
     * )
     * 
     * @Template()
     */
    public function deleteAction(Request $Request, MenuType $Type) {
        
        $form = $this->createForm(new MenuTypeDeleteType($Type))
                        ->handleRequest($Request);
        
        if($form->isValid()) {
            $chosen = false;
            
            if(true === $form->get('setNull')->getData()){
                $newTypeId = null;
                $chosen = true;
            } else if(null !== ($NewType = $form->get('newType')->getData())){
                $newTypeId = $NewType->getId();
                $chosen = true;
            }
            
            if($chosen) {
                $RepoMenu = $this->getDoctrine()->getRepository('TomSiteBundle:Menu');
                $modifiedMenu = $RepoMenu->moveToCategory($Type>getId(), $newTypeId);
                
                $em = $this->getDoctrine()->getManager();
                $em->remove($Type);
                $em->flush();
                
                $this->addFlash('success', sprintf('Typ menu został usunięty.'));
                $this->addFlash('warning', sprintf('Zostało zmodyfikowanych %d pozycji menu.', $modifiedMenu));
                
                return $this->redirect($this->generateUrl('tom_admin_menu_types'));
                
            } else{
                $this->addFlash('error', 'Musisz wybrać nowy typ menu lub zaznaczyć checkbox.');
            }
        }          
        
        return array(
            'pageTitle' => 'Typy menu <small>usuń</small>',
            'currPage'  => 'types',
            'type'      => $Type,
            'form'      => $form->createView()
        );
    }
}
