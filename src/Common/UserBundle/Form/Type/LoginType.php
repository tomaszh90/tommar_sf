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
                        'placeholder' => 'Użytkownik',
                        'autocomplete' => 'off'
                    )
                ))
                ->add('password', 'password', array(
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Hasło',
                        'autocomplete' => 'off'
                    )
                ))
                ->add('remember_me', 'checkbox', array(
                    'label' => 'Zapamiętaj mnie',
                    'label_attr' => array(
                        'id' => 'rememberMe'
                    )
                ))
                ->add('save', 'submit', array(
                    'label' => 'Zaloguj',
                    'attr' => array(
                        'class' => 'btn btn-primary btn-block btn-flat'
                    )
                ));
    }
    
}