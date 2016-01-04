<?php

namespace Tom\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route(
 *       "/artykuly"
 * )
 */
class ArticlesController extends Controller {

    protected $itemsLimit = 7;
    
    /**
     * @Route(
     *       "/{category}/{page}",
     *      defaults = {"page" = 1, "category" = NULL},
     *       name="tom_site_articles",
     *      requirements = {"page" = "\d+"}
     * )
     * 
     * @Template()
     */
    public function indexAction(Request $Request, $category, $page) {
        
        $queryParams = array(
            'search' => $Request->query->get('search'),
            'tagSlug' => $Request->query->get('tagSlug'),
            'categorySlug' => $category,
            'status' => 'published',
            'orderBy' => 'a.publishedDate',
            'orderDir' => 'DESC'
        );
        $pagination = $this->getPaginatedArticles($queryParams, $page);

       

        return array(
            'pagination' => $pagination,
            'queryParams' => $queryParams,
            'pageTitle' => 'Aktualności'
        );
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
    public function articleAction($id) {
        $RepoArticle = $this->getDoctrine()->getRepository('TomSiteBundle:Article');
        $Article = $RepoArticle->getPublishedArticle($id);

        if (NULL == $Article) {
            throw $this->createNotFoundException('Nie znaleziono takiego artykułu.');
        }

        return array(
            'item' => $Article,
            'pageTitle' => 'Aktualności'
        );
    }
    
    protected function getPaginatedArticles(array $params = array(), $page) {
        $RepoArticle = $this->getDoctrine()->getRepository('TomSiteBundle:Article');
        
        $qb = $RepoArticle->getQueryBuilder($params);
        
        if(NULL == $qb) {
            $qb = array();
        }
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $this->itemsLimit);
        
        return $pagination;
    }

}