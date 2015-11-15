<?php

namespace Tom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Tom\SiteBundle\Entity\Seo;

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
    
//   /**
//    * @Route(
//    *       "/seo",
//    *       name="tom_admin_seo"
//    * )
//    * 
//    * @Template("TomAdminBundle:Settings/Seo:Seo.html.twig")
//    */
//    public function SeoAction(Request $request) //Kompatybilność Symfony 3
//    {
//       $id=1; // brak tworzenia tylko edycja istniejącego wpisu id1
//      // $seo = new Seo();  // Entity Seo
//        //$request = $this->get('request');  // nie potrzebny request
//
//        if (is_null($id)) {
//            $postData = $request->get('Seo');
//            $id = $postData['id'];
//        }
//
//        $em = $this->getDoctrine()->getManager();
//        $seo = $em->getRepository('TomSiteBundle:Seo')->find($id);
//        $form = $this->createForm(new SeoType(), $seo);
//
//        if ($request->getMethod() == 'POST') {
//            //$form->handleRequest($request);  // vs $form->bind($request); ?
//            $form->bind($request);
//            if ($form->isValid()) {
//                // perform some action, such as save the object to the database
//                            $em->persist($seo);
//                            $em->flush();
//                     $this->addFlash('TomAdminBundle_form_notice','Zmiany zostały zapisane poprawnie!');
//                return $this->redirect($this->generateUrl('tom_admin_settings'));
//            }
//        }
//
//
//        return $this->render('TomAdminBundle:Settings/Seo:Seo.html.twig', array(
//            'pageTitle' => 'SEO <small>ustawienia witryny</small>',
//            'form' => $form->createView()
//        ));
//    }
    
   /**
    * @Route(
    *       "/seo/{id}",
    *       name="tom_admin_seo",
    *       defaults = {"id" = 1},
    *       requirements = {"id" = "\d+"}
    * )
    * 
    * @Template("TomAdminBundle:Settings/Seo:Seo.html.twig")
    */
    public function SeoAction(Request $Request, $id, Seo $Seo_site = NULL) {
        
        if(null == $Seo_site){
            $Seo_site = new Seo();
            $Seo_site->setAuthor($this->getUser());
            $newSeoForm = TRUE;
        }
        
        $RepoSeo = $this->getDoctrine()->getRepository('TomSiteBundle:Seo');
        $Seo     = $RepoSeo->find($id);
        
        if(NULL == $Seo) {
            throw $this->createNotFoundException('Nie znaleziono takiego rekordu');
        }
        
        $form = $this->createForm(new \Tom\AdminBundle\Form\Type\SeoType, $Seo);
        
        if($Request->isMethod('POST')) {
            $Session = $this->get('session');
            $form->handleRequest($Request);
            
            if($form->isValid()) {
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($Seo);
                $em->flush();
         
                $this->addFlash('success','Zmiany zostały zapisane poprawnie!');
                return $this->redirect($this->generateUrl('tom_admin_seo'));
            }
            else {
                $this->addFlash('error','Popraw błędy formularza');
            }
        }
        
        return array(
            'pageTitle' => 'SEO <small>ustawienia witryny</small>',
            'form' => $form->createView(),
            'seo' => $Seo_site
        );
    }
    
   /**
    * @Route(
    *       "/javascript/{id}",
    *       name="tom_admin_javascript",
    *       defaults = {"id" = 1},
    *       requirements = {"id" = "\d+"}
    * )
    * 
    * @Template("TomAdminBundle:Settings/Javascript:Javascript.html.twig")
    */
    public function JavascriptAction(Request $Request, $id) {
        
        $RepoScript = $this->getDoctrine()->getRepository('TomSiteBundle:Javascript');
        $Script     = $RepoScript->find($id);
        
        if(NULL == $Script) {
            throw $this->createNotFoundException('Nie znaleziono takiego rekordu');
        }
        
        $form = $this->createForm(new \Tom\AdminBundle\Form\Type\JavascriptType(), $Script);
        
        if($Request->isMethod('POST')) {
            $Session = $this->get('session');
            $form->handleRequest($Request);
            
            if($form->isValid()) {
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($Script);
                $em->flush();
         
                $this->addFlash('success','Zmiany zostały zapisane poprawnie!');
                return $this->redirect($this->generateUrl('tom_admin_javascript'));
            }
            else {
                $this->addFlash('error','Popraw błędy formularza');
            }
        }
        
        return array(
            'pageTitle' => 'Script <small>ustawienia witryny</small>',
            'form' => $form->createView()
        );
    }
}
