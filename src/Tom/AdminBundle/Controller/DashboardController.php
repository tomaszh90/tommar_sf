<?php

namespace Tom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tom\AdminBundle\Form\Type\MessengerType;
use Tom\SiteBundle\Entity\Messenger;

class DashboardController extends Controller
{
   /**
    * @Route(
    *       "/",
    *       name="tom_admin_dashboard"
    * )
    * 
    * @Template()
    */
    public function indexAction(Messenger $messenger = NULL)
    {
        $messenger = new Messenger();
        $messenger->setAuthorMess($this->getUser());
        $messenger->setUpdateDate(new \DateTime());
        $request = $this->getRequest();
        
    $messengerform = $this->createForm( new MessengerType(), $messenger );
        $em = $this->getDoctrine()->getManager();
     $entities = $em->getRepository('TomSiteBundle:Messenger')->findBy([], ['id' => 'DESC']);
    if ( $request->isMethod( 'POST' ) ) {
        $Session = $this->get('session');
        $messengerform->submit( $request ); 
        if ( $messengerform->isValid( ) ) { 
           /*
            * $data['contents']
            */
               // $em = $this->getDoctrine()->getManager();
                $em->persist($messenger);
                $em->flush();
                $data = $messengerform->getData(); 
                //$this->addFlash('success','Git!');
                $response['success'] = true;
        }else{
            $response['success'] = false;
            $response['errors'] = 'blah';
        }
            return new JsonResponse( $response );
        }
        
        return array(
        'messengerform' => $messengerform->createView(),
        'pageTitle' => 'Dashboard <small>najnowsze zdarzenia</small>',
        'entities' => $entities,
        );
}
        
        
        
    
 /*       $user = $this->getUser();

         // the above is a shortcut for this
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $postmessenger = $this->createForm( new MessengerType() );
 
    return array(
      'postmessenger' => $postmessenger->createView(),
        'pageTitle' => 'Dashboard <small>najnowsze zdarzenia</small>',
    );
        return array(
            'pageTitle' => 'Dashboard <small>najnowsze zdarzenia</small>',
            'user' => $user
        );
    }*/
}
