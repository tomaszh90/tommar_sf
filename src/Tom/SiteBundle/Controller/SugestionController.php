<?php

namespace Tom\SiteBundle\Controller;

use Tom\SiteBundle\Entity\Seo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tom\SiteBundle\Entity\Sugestion;
use Tom\AdminBundle\Form\Type\SugestionType;
/**
 * @Route("/")
 */
class SugestionController extends Controller {
    /**
     * @Route(
     *       "/",
     *       name="tom_site_sugestion",
     * )
     * 
     * @Template()
     */
    public function indexAction(Request $Request, Sugestion $Sugestion = NULL) {
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

        return array(
            'form' => $form->createView(),
            'sugestion' => $Sugestion,
            'pageTitle' => 'Aktualności'
        );
    }
}
