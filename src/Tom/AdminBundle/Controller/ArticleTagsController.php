<?php

namespace Tom\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Tom\SiteBundle\Entity\Tag;
use Tom\AdminBundle\Form\Type\TaxonomyType;


class ArticleTagsController extends Controller
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
        
        $queryParams = array(
            'nameLike' => $Request->query->get('nameLike'),
            'orderBy' => 't.id',
            'orderDir' => 'DESC'
        );
        
        $RepoTag = $this->getDoctrine()->getRepository('TomSiteBundle:Tag');
        $qb = $RepoTag->getQueryBuilder($queryParams);
        
        $paginationLimit = $this->container->getParameter('admin.pagination_limit');
        $limits = array(2, 5, 10, 15);
        $limit = $Request->query->get('limit', $paginationLimit);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
        
        return array(
            'pageTitle' => 'Tagi <small>lista wpisów</small>',
            
            'currPage' => 'taxonomies',
            'queryParams' => $queryParams,
            
            'pagination' => $pagination,
            
            'limits' => $limits,
            'currLimit' => $limit,
            
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

            $this->addFlash('success', $flashMessage);

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
     *      "/api/dodaj",
     *      name="tom_admin_api_tag_add",
     *      requirements={
     *          "_format": "json",
     *          "methods": "POST"
     *      }
     * )
     */
    public function addTagApiAction(Request $Request) {
        $data = json_decode($Request->getContent(), true);
        $tagValue  = trim($data['tag']);
        
        if(strlen($tagValue) >= 3) {       
            $RepoTags = $this->getDoctrine()->getRepository('TomSiteBundle:Tag');
            $findTag = $RepoTags->findBySlug(\Tom\SiteBundle\Libs\Utils::sluggify($tagValue));
            
            if(NULL == $findTag) {
                $Tag = new Tag();
                $Tag->setName($tagValue);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($Tag);
                $em->flush();
                
                $result = array(
                    'id' => $Tag->getId(),
                    'name' => $Tag->getName(),
                ); 
            } else {
                $result = false;
            }
            
        } else {
            $result = false;
        }
 
        return new JsonResponse($result);  
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
            $this->addFlash('error', 'Niepoprawny token akcji!');
            
        } else{
            
            $Tag = $this->getDoctrine()->getRepository('TomSiteBundle:Tag')->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($Tag);
            $em->flush();
            
            $this->addFlash('success', 'Poprawnie usunięto tag.');
        }
        
        return $this->redirect($this->generateUrl('tom_admin_tags'));
    }
}