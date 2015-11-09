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
use Common\UserBundle\Form\Type\RegisterUserType;
use Common\UserBundle\Entity\User;

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
            $this->addFlash('error', $loginError->getMessage());
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
                    
                    $this->addFlash('success', 'Informacje dotyczące resetowania hasła zostały wysłane na adres e-mail.');
                    
                    return $this->redirect($this->generateUrl('site_login'));
                    

                } catch (UserException $exc) {
                    $error = new FormError($exc->getMessage());
                    $rememberPasswdForm->get('email')->addError($error);
                }

           }
       }
       

        $User = new User();
        $registerUserForm = $this->createForm(new RegisterUserType(), $User);
        
        if($Request->isMethod('POST')){
            $registerUserForm->handleRequest($Request);
            
            if($registerUserForm->isValid()){
                
                try{
                    
                    $userManager = $this->get('user_manager');
                    $userManager->registerUser($User);

                    $this->addFlash('success', 'Konto zostało utworzone. Na Twoją skrzynkę pocztową została wysłana wiadomość aktywacyjna.');
                    
                    return $this->redirect($this->generateUrl('site_login'));
                    
                } catch (UserException $exc) {
                    $this->addFlash('error', $exc->getMessage());
                }
                
            }
        }
        
        return array(
            'pageTitle'            => '<strong>Timeto</strong> Panel',
            'loginForm'            => $loginForm->createView(),
            'rememberPasswdForm'   => $rememberPasswdForm->createView(),
            'registerUserForm'     => $registerUserForm->createView()
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
            
            $this->addFlash('success', 'Nowe hasło zostało wysłane na Twój adres e-mail.');
            
        } catch (UserException $exc) {
            
            $this->addFlash('error', $exc->getMessage());
            
        }
        
        return $this->redirect($this->generateUrl('site_login'));
    }

   /**
    * @Route(
    *       "/account-activation/{actionToken}",
    *       name="user_activateAccount"
    * )
    * 
    */
    public function activateAccountAction($actionToken)
    {
        try {
            
            $userManager = $this->get('user_manager');
            $userManager->activateAccount($actionToken);
            
            $this->addFlash('success', 'Twoj konto zostało aktywowane.');
            
        } catch (UserException $exc) {
            
            $this->addFlash('error', $exc->getMessage());
            
        }
        
        return $this->redirect($this->generateUrl('site_login'));
    }
    
}
