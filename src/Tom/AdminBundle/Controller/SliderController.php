<?php

namespace Tom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Tom\SiteBundle\Entity\Slider;
use Tom\AdminBundle\Form\Type\SliderType;

class SliderController extends Controller
{
    
    private $deleteTokenName = 'delete-slider-%d';
    
    /**
     * @Route(
     *      "/form/{id}", 
     *      name="tom_admin_slider_form",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=NULL}
     * )
     * 
     * @Template()
     */
    public function formAction(Request $Request, Slider $Slider = NULL) {

        if(null == $Slider){
            $Slider = new Slider();
            $newSliderForm = TRUE;
        }
        
        $form = $this->createForm(new SliderType(), $Slider);
        
        $form->handleRequest($Request);
        if($form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($Slider);
            $em->flush();

            $message = (isset($newSliderForm)) ? 'Poprawnie dodano nowy slide.': 'Slide został zaktualizowany.';
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('tom_admin_slider_form', array(
                'id' => $Slider->getId()
            )));
        }
        
        return array(
            'pageTitle' => (isset($newSliderForm) ? 'Slider <small>utwórz nowy</small>' : 'Slider <small>edycja slider</small>'),
            'currPage' => 'sliders',
            'form' => $form->createView(),
            'slider' => $Slider,
        );
    }
    
    /**
     * @Route(
     *      "/{status}/{page}", 
     *       name="tom_admin_slider",
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
            'categoryId' => $Request->query->get('categoryId'),
            'status' => $status
        );
        
        $RepoSlider = $this->getDoctrine()->getRepository('TomSiteBundle:Slider');
        
        $statistics = $RepoSlider->getStatistics();
        
        $qb = $RepoSlider->getQueryBuilder($queryParams);
        
        $paginationLimit = $this->container->getParameter('admin.pagination_limit');
        $limits = array(2, 5, 10, 15);
        
        $limit = $Request->query->get('limit', $paginationLimit);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
       
        $categoriesList = $this->getDoctrine()->getRepository('TomSiteBundle:SliderCategory')->getAsArray();
        
        $statusesList = array(
            'Opublikowane' => 'published',
            'Nieopublikowane' => 'unpublished',
            'Wszystkie' => 'all'
        );
        
        return array(
            'pageTitle' => 'Slider <small>lista</small>',
            'currPage' => 'sliders',
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
     *      name="tom_admin_slider_delete",
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
            
            $Slider = $this->getDoctrine()->getRepository('TomSiteBundle:Slider')->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($Slider);
            $em->flush();
            
            $this->addFlash('success', 'Poprawnie usunięto slide.');
        }
        
        return $this->redirect($this->generateUrl('tom_admin_slider'));
    }
}
