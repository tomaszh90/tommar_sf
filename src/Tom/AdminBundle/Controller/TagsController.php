<?php

namespace Tom\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Tom\SiteBundle\Entity\Tag;
use Tom\AdminBundle\Form\Type\TaxonomyType;


class TagsController extends Controller
{
    private $deleteTokenName = 'delete-%d-tag';
    
    /**
     * @Route(
     *      "/{page}", 
     *      name="tom_admin_tags",
     *      requirements={"page"="\d+"},
     *      defaults={"page"=1}
     * )
     * 
     * @Template()
     */
    public function indexAction(Request $Request, $page) {
        
        $RepoTag = $this->getDoctrine()->getRepository('TomSiteBundle:Tag');
        
        $qb = $RepoTag->getQueryBuilder();
        
        $limit = $this->container->getParameter('admin.pagination_limit');
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
        
        return array(
            'pageTitle' => 'Tagi <small>lista wpisów</small>',
            'currPage' => 'taxonomies',
            'pagination' => $pagination,
            'deleteTokenName' => $this->deleteTokenName,
            'csrfProvider' => $this->get('form.csrf_provider')
        );
    }
    
    
    /**
     * @Route(
     *      "/form/{id}", 
     *      name="tom_admin_tag_form",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=NULL}
     * )
     * 
     * @Template()
     */
    public function formAction(Request $Request, Tag $Tag = NULL) {
        
        if(NULL === $Tag){
            $Tag = new Tag();
            $newTag = TRUE;
        }
        
        $form = $this->createForm(new TaxonomyType(), $Tag);
        
        $form->handleRequest($Request);
        
        if($form->isValid()){
                
            $em = $this->getDoctrine()->getManager();
            $em->persist($Tag);
            $em->flush();

            $flashMessage = (isset($newTag)) ? 'Poprawnie dodano nowy tag.' : 'Tag został zaktualizowany.';

            $this->get('session')->getFlashBag()->add('success', $flashMessage);

            return $this->redirect($this->generateUrl('tom_admin_tag_form', array(
                'id' => $Tag->getId()
            )));

        }
        
        return array(
            'pageTitle' => 'Tagi <small>dodaj nowy</small>',
            'currPage' => 'taxonomies',
            'form' => $form->createView(),
            'tag' => $Tag
        );
    }
    
    
    /**
     * @Route(
     *      "/delete/{id}/{token}", 
     *      name="tom_admin_tag_delete",
     *      requirements={"id"="\d+"}
     * )
     * 
     * @Template()
     */
    public function deleteAction($id, $token) {
        
        $tokenName = sprintf($this->deleteTokenName, $id);
        $csrfProvider = $this->get('form.csrf_provider');
        
        if(!$csrfProvider->isCsrfTokenValid($tokenName, $token)){
            $this->get('session')->getFlashBag()->add('error', 'Niepoprawny token akcji!');
            
        }else{
            
            $Tag = $this->getDoctrine()->getRepository('TomSiteBundle:Tag')->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($Tag);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Poprawnie usunięto tag.');
        }
        
        return $this->redirect($this->generateUrl('tom_admin_tags'));
    }
}
