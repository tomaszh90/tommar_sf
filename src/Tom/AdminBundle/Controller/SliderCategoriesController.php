<?php

namespace Tom\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Tom\SiteBundle\Entity\SliderCategory;
use Tom\AdminBundle\Form\Type\SliderCategoryType;
use Tom\AdminBundle\Form\Type\SliderCategoryDeleteType;

class SliderCategoriesController extends Controller {    

    /**
     * @Route(
     *      "/form/{id}", 
     *      name="tom_admin_slider_category_form",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=NULL}
     * )
     * 
     * @Template()
     */
    public function formAction(Request $Request, SliderCategory $Category = NULL) {
        
        if(NULL === $Category){
            $Category = new SliderCategory();
            $newCategory = TRUE;
        }
        
        $form = $this->createForm(new SliderCategoryType(), $Category)   
                    ->handleRequest($Request);
        
        if($form->isValid()){
                
            $em = $this->getDoctrine()->getManager();
            $em->persist($Category);
            $em->flush();

            $message = (isset($newCategory)) ? 'Poprawnie dodano nową kategorię sliderów.': 'Zaktualizowane kategorię sliderów.';
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('tom_admin_slider_category_form', array(
                'id' => $Category->getId()
            )));
        }
        
        return array(
            'pageTitle' => (isset($newCategory) ? 'Kategoria sliderów <small>utwórz nową</small>' : 'Kategoria sliderów <small>edycja</small>'),
            'currPage'  => 'categories',
            'form'      => $form->createView(),
            'category'  => $Category
        );
    }
    
    /**
     * @Route(
     *      "/{page}", 
     *      name="tom_admin_slider_categories",
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
        
        $RepoCategory = $this->getDoctrine()->getRepository('TomSiteBundle:SliderCategory');
        $qb = $RepoCategory->getQueryBuilder();
        
        $paginationLimit = $this->container->getParameter('admin.pagination_limit');
        $limits = array(2, 5, 10, 15);
        $limit = $Request->query->get('limit', $paginationLimit);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
        
            
        return array(
            'pageTitle' => 'Kategorie sliderów <small>lista kategorii</small>',
            
            'currPage' => 'categories',
            'queryParams' => $queryParams,
            
            'pagination' => $pagination,
            
            'limits' => $limits,
            'currLimit' => $limit,
        );
    }
    
    
    /**
     * @Route(
     *      "/usun/{id}", 
     *      name="tom_admin_slider_category_delete"
     * )
     * 
     * @Template()
     */
    public function deleteAction(Request $Request, SliderCategory $Category) {
        
        $form = $this->createForm(new SliderCategoryDeleteType($Category))
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
                $RepoSlider = $this->getDoctrine()->getRepository('TomSiteBundle:Slider');
                $modifiedSliders = $RepoSlider->moveToCategory($Category->getId(), $newCategoryId);
                
                $em = $this->getDoctrine()->getManager();
                $em->remove($Category);
                $em->flush();
                
                $this->addFlash('success', sprintf('Kategoria została usunięta.'));
                $this->addFlash('warning', sprintf('Zostało zmodyfikowanych %d sliderów.', $modifiedSliders));
                
                return $this->redirect($this->generateUrl('tom_admin_slider_categories'));
                
            } else{
                $this->addFlash('error', 'Musisz wybrać nowa kategorię lub zaznaczyć checkbox.');
            }
        }          
        
        return array(
            'pageTitle' => 'Kategoria sliderów <small>usuń</small>',
            'currPage'  => 'categories',
            'category'  => $Category,
            'form'      => $form->createView()
        );
    }
}
