<?php

namespace Tom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Tom\SiteBundle\Entity\Sugestion;
use Tom\AdminBundle\Form\Type\SugestionType;

class SugestionController extends Controller {

    /**
     * @Route(
     *       "/",
     *       name="tom_admin_sugestion",
     *       requirements={"page"="\d+"},
     *       defaults={"status"="all", "page"=1}
     *      
     * )
     *    
     * @Template()
     */
    public function indexAction(Request $Request,$status , $page) {
        
        $queryParams = array(
            'sugestionLike' => $Request->query->get('sugestionLike'),
            'status' => $status
        );
        $RepoSugestion = $this->getDoctrine()->getRepository('TomSiteBundle:Sugestion');
        
        $statistics = $RepoSugestion->getStatistics();
        
        $qb = $RepoSugestion->getQueryBuilder($queryParams);
        
        $paginationLimit = $this->container->getParameter('admin.pagination_limit');
        $limits = array(2, 5, 10, 15);
        
        $limit = $Request->query->get('limit', $paginationLimit);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);

        $statusesList = array(
            'Odczytane' => 'read',
            'Zatwierdzone' => 'approved',
            'Usunięte' => 'removed',
            'Wszystkie' => 'all'
        );
        
        return array(
            'pageTitle' => 'Sugestie <small>lista</small>',
            'currPage' => 'sugestion',
            'queryParams' => $queryParams,
            
            'limits' => $limits,
            'currLimit' => $limit,
            
            'pagination' => $pagination,
            'statusesList' => $statusesList,
            'currStatus' => $status,
            'statistics' => $statistics,
            
        );
       
    }
    
}
