<?php

namespace Tom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Tom\SiteBundle\Entity\Sugestion;
use Tom\AdminBundle\Form\Type\SugestionType;

class SugestionController extends Controller {
    
    private $deleteTokenName = 'delete-sugestion-%d';

    /**
     * @Route(
     *       "/{status}",
     *       name="tom_admin_sugestion",
     *       requirements={"page"="\d+"},
     *       defaults={"status"="all", "page"=1}
     *      
     * )
     *    
     * @Template()
     */
    public function indexAction(Request $Request, $status , $page) {
        
        $queryParams = array(
            'nameSugestionLike' => $Request->query->get('nameSugestionLike'),
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
            'Niezatwierdzone' => 'notapproved',
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
    
    /**
     * @Route(
     *      "/form/{id}", 
     *      name="tom_admin_sugestion_form",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=NULL}
     * )
     * 
     * @Template()
     */
    public function formAction(Request $Request, Sugestion $Sugestion = NULL) {

        if(null == $Sugestion){
            $Sugestion = new Sugestion();
            $newSugestionForm = TRUE;
        }
        
        $form = $this->createForm(new SugestionType(), $Sugestion);
        
        $form->handleRequest($Request);
        if($form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($Sugestion);
            $em->flush();

            $message = (isset($newSugestionForm)) ? 'Poprawnie dodano nowy sugestię.': 'Sugestia została zaktualizowana.';
            $this->addFlash('success', $message);

            return $this->redirect($this->generateUrl('tom_admin_sugestion_form', array(
                'id' => $Sugestion->getId()
            )));
        }
        //emailllll test
        if ($Request->getMethod() == "POST") {
            $Subject = $Request->get("Subject");
            $email = $Request->get("email");
            $message = $Request->get("message");
            
            $mailer = $this->container->get('mailer');
            $transport = \Swift_SmtpTransport::newInstance('mail.timeto.kylos.pl', 465, 'ssl')
                    ->setUsername('redakcja@timeto.pl')
                    ->setPassword('*****');
            $mailer = \Swift_Mailer::newInstance($transport);
            $message = \Swift_Message::newInstance('Test')
                    ->setSubject($Subject)
                    ->setFrom('redakcja@timeto.pl')
                    ->setTo($email)
                    ->setContentType("text/html")
                    ->setBody($message);
            $this->get('mailer')->send($message);
        }
        // koniec email test
        return array(
            'pageTitle' => (isset($newSugestionForm) ? 'Sugestia <small>utwórz nowy</small>' : 'Sugestia <small>edycja</small>'),
            'currPage' => 'sugestions',
            'form' => $form->createView(),
            'sugestion' => $Sugestion,
        );
    }
    
}
