<?php

namespace Common\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Form\FormError;
use Common\UserBundle\Exception\UserException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Common\UserBundle\Form\Type\LoginType;
use Common\UserBundle\Form\Type\RememberPasswordType;

/**
 * @Route("/")
 */
class LoginController extends Controller
{
    
   /**
    * @Route(
    *       "/login",
    *       name="site_login"
    * )
    * 
    * @Template()
    */
    public function loginAction(Request $Request)
    {
        $Session = $this->get('session');
        
        if($Request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $loginError = $Request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $loginError = $Session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        }
        
        if(isset($loginError)) {
            $this->get('session')->getFlashBag()->add('error', $loginError->getMessage());
        }
        
        $loginForm = $this->createForm(new LoginType(), array(
            'username' => $Session->get(SecurityContextInterface::LAST_USERNAME)
        ));
        
        $rememberPasswdForm = $this->createForm(new RememberPasswordType());
        
       if($Request->isMethod('POST')) {
           $rememberPasswdForm->handleRequest($Request);
           
           if($rememberPasswdForm->isValid()) {
                try {
                    
                    $userEmail = $rememberPasswdForm->get('email')->getData();

                    $userManager = $this->get('user_manager');
                    $userManager->sendResetPasswordLink($userEmail);
                    
                    $this->get('session')->getFlashBag()->add('success', 'Informacje dotyczące resetowania hasła zostały wysłane na adres e-mail.');
                    
                    return $this->redirect($this->generateUrl('site_login'));
                    

                } catch (UserException $exc) {
                    $error = new FormError($exc->getMessage());
                    $rememberPasswdForm->get('email')->addError($error);
                }

           }
       }
        
        return array(
            'pageTitle'            => '<strong>Timeto</strong> Panel',
            'loginForm'            => $loginForm->createView(),
            'rememberPasswdForm'   => $rememberPasswdForm->createView()
        );
    }
    
   /**
    * @Route(
    *       "/reset-password/{actionToken}",
    *       name="user_resetPassword"
    * )
    * 
    */
    public function resetPasswordAction($actionToken)
    {
        try {
            
            $userManager = $this->get('user_manager');
            $userManager->resetPassword($actionToken);
            
            $this->get('session')->getFlashBag()->add('success', 'Nowe hasło zostało wysłane na Twój adres e-mail.');
            
        } catch (Exception $exc) {
            
            $this->get('session')->getFlashBag()->add('error', $exc->getMessage());
            
        }
        
        return $this->redirect($this->generateUrl('site_login'));
    }
    
}
