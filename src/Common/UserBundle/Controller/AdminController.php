<?php

namespace Common\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Common\UserBundle\Exception\UserException;
use Common\UserBundle\Entity\User;
use Common\UserBundle\Form\Type\ManageUserType;
use Common\UserBundle\Form\Type\ManageUserChangePasswordType;


class AdminController extends Controller
{
    private $deleteTokenName = 'delete-user-%d';
    
    /**
     * @Route(
     *      "/form/{id}", 
     *      name="common_user_admin_user_form",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=NULL}
     * )
     * 
     * @Template()
     */
    public function formAction(Request $Request, User $User = NULL) {
        
        if(NULL === $User){
            $User = new User();
            $newUser = TRUE;
        } else {
            $newUser = FALSE;
        }
                
        $manageUserForm = $this->createForm(new ManageUserType(), $User , array(
            'register' => $newUser
        ));
        
        if($Request->isMethod('POST') && $Request->request->has('manageUser')){
            $manageUserForm->handleRequest($Request);

            if($manageUserForm->isValid()){
                
                if($newUser == TRUE){
                    $userManager = $this->get('user_manager');
                    $userManager->registerUser($User);
                } else {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($User);
                    $em->flush();
                }
                
                $flashMessage = ($newUser == TRUE) ? 'Poprawnie dodano nowego użytkownika.' : 'Zaktualizowano konto użytkownika';
                $this->addFlash('success', $flashMessage);

                return $this->redirect($this->generateUrl('common_user_admin_user_form', array(
                    'id' => $User->getId()
                )));
            }
        }
        
        if($newUser == FALSE){
            $changePasswdForm = $this->createForm(new ManageUserChangePasswordType(), $User);

            if($Request->isMethod('POST') && $Request->request->has('changePassword')){
                $changePasswdForm->handleRequest($Request);

                if($changePasswdForm->isValid()){

                    try {
                        $userManager = $this->get('user_manager');
                        $userManager->changePassword($User);

                        $this->addFlash('success',sprintf("Hasło użytkownika '%s' zostało zmienione", $User->getUsername()));
                        return $this->redirect($this->generateUrl('common_user_admin_user_form', array(
                            'id' => $User->getId()
                        )));

                    } catch (UserException $ex) {
                        $this->addFlash('error', $ex->getMessage());
                    }

                }else{
                    $this->addFlash('error', 'Popraw błędy formularza2');
                }
            }
        }
        
        
        return array(
            'pageTitle' => ($newUser == TRUE ? 'Zarządzanie użytkownikami <small>utwórz nowego</small>' : 'Zarządzanie użytkownikami <small>edycja użytkownika</small>'),
            'currPage' => 'users',
            'manageUserForm' => $manageUserForm->createView(),
            'changePasswdForm' => ($newUser == TRUE ? '' : $changePasswdForm->createView()),
            'user' => $User
        );
    }
    
    /**
     * @Route(
     *      "/{page}", 
     *      name="common_user_admin_users",
     *      requirements={"page"="\d+"},
     *      defaults={"page"=1}
     * )
     * 
     * @Template()
     */
    public function indexAction(Request $Request, $page) {
        
        $queryParams = array(
            'usernameLike' => $Request->query->get('usernameLike'),
        );
        
        $RepoUsers = $this->getDoctrine()->getRepository('CommonUserBundle:User');
        $qb = $RepoUsers->getQueryBuilder($queryParams);
        
        $paginationLimit = $this->container->getParameter('admin.pagination_limit');
        $limits = array(2, 5, 10, 15);
        $limit = $Request->query->get('limit', $paginationLimit);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
        
        return array(
            'pageTitle' => 'Zarządzanie użytkownikami <small>lista użytkowników</small>',
            
            'currPage' => 'users',
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
     *      "/usun/{id}/{token}", 
     *      name="common_user_admin_user_delete",
     *      requirements={"id"="\d+"}
     * )
     * 
     * @Template()
     */
    public function deleteAction($id, $token) {
        
        $tokenName = sprintf($this->deleteTokenName, $id);
        $csrfProvider = $this->get('form.csrf_provider');
        
        if(!$csrfProvider->isCsrfTokenValid($tokenName, $token)){
            $this->addFlash('error', 'Niepoprawny token akcji.');
            
        }else{
            
            $User = $this->getDoctrine()->getRepository('CommonUserBundle:User')->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($User);
            $em->flush();
            
            $this->addFlash('success', 'Poprawnie usunięto użytownika.');
        }
        
        return $this->redirect($this->generateUrl('common_user_admin_users'));
    }
    
}
