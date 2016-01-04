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

    protected $itemsLimit = 10;

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
        
        $ArticleNext = $RepoArticle->getNextPrevArticle(array(
            'articleId' => $Article->getId(),
            'categoryId' => $Article->getCategory()->getId(),
            'orderBy' => 'a.id',
            'orderDir' => 'DESC',
            'sort' => 'next'
        ));
        $ArticlePrev = $RepoArticle->getNextPrevArticle(array(
            'articleId' => $Article->getId(),
            'categoryId' => $Article->getCategory()->getId(),
            'orderBy' => 'a.id',
            'orderDir' => 'DESC',
            'sort' => 'prev'
        ));

        return array(
            'item' => $Article,
            'prevnextArticle' => array(
                'prev' => $ArticlePrev,
                'next' => $ArticleNext
            ),
            'pageTitle' => 'Artykuły'
        );
    }
    
    /**
     * @Route(
     *      "/{category}/{page}",
     *      defaults = {"page" = 1},
     *      name="tom_site_articles_category",
     *      requirements = {"page" = "\d+"}
     * )
     * 
     * @Template("TomSiteBundle:Articles:index.html.twig")
     */
    public function categoryAction(Request $Request, $category, $page) {
        
        $queryParams = array(
            'search' => $Request->query->get('search'),
            'tagSlug' => $Request->query->get('tagSlug'),
            'categorySlug' => $category,
            'status' => 'published',
            'orderBy' => 'a.publishedDate',
            'orderDir' => 'DESC'
        );
        $pagination = $this->getPaginatedArticles($queryParams, $page);

        $listTitle = 'Artykuły';
        
        if(NULL !== $category) {
            $RepoCategory = $this->getDoctrine()->getRepository('TomSiteBundle:ArticleCategory');
            $CategoryFind = $RepoCategory->findOneBySlug($category);
            if (NULL == $CategoryFind) {
                return $this->redirect($this->generateUrl('tom_site_articles'));
            }
            $listTitle = $CategoryFind->getName();
        }
       

        return array(
            'pagination' => $pagination,
            'queryParams' => $queryParams,
            'pageTitle' => 'Aktualności',
            'listTitle' => $listTitle
        );
    }
    
    /**
     * @Route(
     *      "/{page}",
     *      defaults = {"page" = 1},
     *      name="tom_site_articles",
     *      requirements = {"page" = "\d+"}
     * )
     * 
     * @Template()
     */
    public function indexAction(Request $Request, $page) {
        
        $queryParams = array(
            'search' => $Request->query->get('search'),
            'tagSlug' => $Request->query->get('tagSlug'),
            'status' => 'published',
            'orderBy' => 'a.publishedDate',
            'orderDir' => 'DESC'
        );
        $pagination = $this->getPaginatedArticles($queryParams, $page);

        $listTitle = 'Artykuły';

        return array(
            'pagination' => $pagination,
            'queryParams' => $queryParams,
            'pageTitle' => 'Aktualności',
            'listTitle' => $listTitle
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