<?php

namespace Tom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tom\AdminBundle\Form\Type\MessengerType;
use Tom\SiteBundle\Entity\Messenger;

class DashboardController extends Controller {

    /**
     * @Route(
     *       "/",
     *       name="tom_admin_dashboard"
     * )
     *    private $date;
     * @Template()
     */
    public function indexAction(Messenger $messenger = NULL) {
        $messenger = new Messenger();
        $messenger->setAuthorMess($this->getUser());
        $messenger->setUpdateDate(new \DateTime());
        $request = $this->getRequest();

        $messengerform = $this->createForm(new MessengerType(), $messenger);
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('TomSiteBundle:Messenger')->findBy([], ['id' => 'DESC'], 10, 0);
        if ($request->isMethod('POST')) {
            $Session = $this->get('session');
            $messengerform->submit($request);
            if ($messengerform->isValid()) {
                /*
                 * $data['contents']
                 */
                // $em = $this->getDoctrine()->getManager();
                $em->persist($messenger);
                $em->flush();
                $data = $messengerform->getData();
                //$this->addFlash('success','Git!');
                $response['success'] = true;
                $czas = new \DateTime(date('Y-m-d H:i:s'));
            } else {
                $response['success'] = false;
                $response['errors'] = 'blah';
                $czas = 'error';
            }
           // return new JsonResponse(array('data1' => $czas));
                        return new JsonResponse( $response );

            //$response_new = new JsonResponse();
            //$response_new->setData(
            //        array(
            //            'data' => $this->czas = new \DateTime()
            //));
        }

        return array(
            'messengerform' => $messengerform->createView(),
            'pageTitle' => 'Dashboard <small>najnowsze zdarzenia</small>',
            'entities' => $entities,
        );
    }


    /**
     * @Route(
     *      "/api/onlive",
     *      name="tom_admin_api_onlive",
     *      requirements={
     *          "_format": "json",
     *          "methods": "POST"
     *      }
     * )
     */
    public function onLiveAction(Request $Request) {

        $jsonArray = json_encode(new \DateTime(date('Y-m-d H:i:s')));
        $dataFileName = 'data.json';
        
        file_put_contents($this->getUploadRootDir().$dataFileName, print_r($jsonArray, TRUE));
        
        return new JsonResponse(true); 
    }
    
    protected function getUploadRootDir(){
        return __DIR__.'/../../../../web/uploads/';
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
      } */
}
