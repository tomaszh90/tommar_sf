<?php

namespace Tom\AdminBundle\Controller;
use Tom\SiteBundle\Entity\Seo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tom\AdminBundle\Form\Type\SeoType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/ustawienia")
 */
class SettingsController extends Controller
{
    
   /**
    * @Route(
    *       "/",
    *       name="tom_admin_settings"
    * )
    * 
    * @Template()
    */
    public function SettingsAction()
    {
        return array(
            'pageTitle' => 'Ustawienia witryny <small>skrót konfiguracji</small>'
        );
    }
    
   /**
    * @Route(
    *       "/seo",
    *       name="tom_admin_seo"
    * )
    * 
    * @Template("TomAdminBundle:Settings/Seo:Seo.html.twig")
    */
    public function SeoAction(Request $request)
    {
        $id=1;
        $request = $this->get('request');

    if (is_null($id)) {
        $postData = $request->get('Seo');
        $id = $postData['id'];
    }

    $em = $this->getDoctrine()->getManager();
    $seo = $em->getRepository('TomSiteBundle:Seo')->find($id);
    $form = $this->createForm(new SeoType(), $seo);

    if ($request->getMethod() == 'POST') {
        $form->bindRequest($request);

        if ($form->isValid()) {
            // perform some action, such as save the object to the database
                        $em->persist($seo);
                        $em->flush();
                 //$this->addFlash('mykyy_form_notice','Artykuł został przesłany poprawnie!');
            return $this->redirect($this->generateUrl('tom_admin_settings'));
        }
    }
    

    return $this->render('TomAdminBundle:Settings/Seo:Seo.html.twig', array(
        'pageTitle' => 'SEO <small>ustawienia witryny</small>',
        'form' => $form->createView()
    ));
//        return array(
//            'pageTitle' => 'SEO <small>ustawienia witryny</small>'
//        );
    }
}
