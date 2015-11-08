<?php

namespace Common\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Common\UserBundle\Form\Type\LoginType;

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
        
        $loginForm = $this->createForm(new LoginType(), array(
            'username' => $Session->get(SecurityContextInterface::LAST_USERNAME)
        ));
        
        
        return array(
            'pageTitle' => '<strong>Timeto</strong> Panel',
            'loginError' => $loginError,
            'loginForm' => $loginForm->createView()
        );
    }
}
