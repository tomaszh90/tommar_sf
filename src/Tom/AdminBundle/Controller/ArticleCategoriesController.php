<?php

namespace Tom\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Tom\SiteBundle\Entity\ArticleCategory;
use Tom\AdminBundle\Form\Type\TaxonomyType;
use Tom\AdminBundle\Form\Type\ArticleCategoryDeleteType;

class ArticleCategoriesController extends Controller {    

    /**
     * @Route(
     *      "/form/{id}", 
     *      name="tom_admin_article_category_form",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=NULL}
     * )
     * 
     * @Template()
     */
    public function formAction(Request $Request, ArticleCategory $Category = NULL) {
        
        if(NULL === $Category){
            $Category = new ArticleCategory();
            $newCategory = TRUE;
        }
        
        $form = $this->createForm(new TaxonomyType(), $Category)   
                    ->handleRequest($Request);
        
        if($form->isValid()){
                
            $em = $this->getDoctrine()->getManager();
            $em->persist($Category);
            $em->flush();

            $message = (isset($newCategory)) ? 'Poprawnie dodano nową kategorię artykułów.': 'Zaktualizowane kategorię artykułów.';
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('tom_admin_article_category_form', array(
                'id' => $Category->getId()
            )));
        }
        
        return array(
            'pageTitle' => (isset($newCategory) ? 'Kategoria artykułów <small>utwórz nową</small>' : 'Kategoria artykułów <small>edycja</small>'),
            'currPage'  => 'taxonomies',
            'form'      => $form->createView(),
            'category'  => $Category
        );
    }
    
    /**
     * @Route(
     *      "/{page}", 
     *      name="tom_admin_article_categories",
     *      defaults={"page"=1}
     * )
     * 
     * @Template()
     */
    public function indexAction($page) {
        
        $RepoCategory = $this->getDoctrine()->getRepository('TomSiteBundle:ArticleCategory');
        $qb = $RepoCategory->getQueryBuilder();
        
        $limit = $this->container->getParameter('admin.pagination_limit');
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
            
        return array(
            'pageTitle' => 'Kategorie artykułów <small>lista wpisów</small>',
            'currPage'   => 'taxonomies',
            'pagination' => $pagination
        );
    }
    
    
    /**
     * @Route(
     *      "/usun/{id}", 
     *      name="tom_admin_article_category_delete"
     * )
     * 
     * @Template()
     */
    public function deleteAction(Request $Request, ArticleCategory $Category) {
        
        $form = $this->createForm(new ArticleCategoryDeleteType($Category))
                        ->handleRequest($Request);
        
        if($form->isValid()) {
            $chosen = false;
            
            if(true === $form->get('setNull')->getData()){
                $newCategoryId = null;
                $chosen = true;
            } else if(null !== ($NewCategory = $form->get('newCategory')->getData())){
                $newCategoryId = $NewCategory->getId();
                $chosen = true;
            }
            
            if($chosen) {
                $RepoArticle = $this->getDoctrine()->getRepository('TomSiteBundle:Article');
                $modifiedArticles = $RepoArticle->moveToCategory($Category->getId(), $newCategoryId);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($Category);
                $em->flush();
                
                $Request->addFlash('success', sprintf('Kategoria została usunięta. Zostało zmodyfikowanych %d artykułów.', $modifiedArticles));
                
                return $this->redirect($this->generateUrl('tom_admin_article_categories'));
                
            } else{
                $Request->addFlash('error', 'Musisz wybrać nowa kategorię lub zaznaczyć checkbox.');
            }
        }          
        
        return array(
            'pageTitle' => 'Kategoria artykułów <small>usuń</small>',
            'currPage'  => 'taxonomies',
            'category'  => $Category,
            'form'      => $form->createView()
        );
    }
}
