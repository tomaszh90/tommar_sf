<?php

namespace Common\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class LoginType extends AbstractType{
    
    public function getName() {
        return 'login';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('username', 'text', array(
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Użytkownik'
                    )
                ))
                ->add('password', 'password', array(
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Hasło'
                    )
                ))
                ->add('remember_me', 'checkbox', array(
                    'label' => 'Zapamiętaj mnie',
                ))
                ->add('save', 'submit', array(
                    'label' => 'Zaloguj'
                ));
    }
    
}