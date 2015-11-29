<?php

namespace Common\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ManageUserType extends AbstractType
{  
    
    public function getName()
    {
        return 'manageUser';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'label' => 'Nazwa użytkownika',
                'attr' => array(
                    'placeholder' => 'Nazwa użytkownika'
                )
            ))
            ->add('name', 'text', array(
                'label' => 'Imię i nazwisko',
                'attr' => array(
                    'placeholder' => 'Imię i nazwisko'
                )
            ))
            ->add('email', 'email', array(
                'label' => 'E-mail',
                'attr' => array(
                    'placeholder' => 'E-mail'
                )
            ));
        
        if ($options['register']) {
            $builder->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'first_options' => array(
                    'label' => 'Hasło',
                    'attr' => array(
                        'placeholder' => 'Hasło',
                        'autocomplete' => 'off'
                    )
                ),
                'second_options' => array(
                    'label' => 'Powtórz hasło',
                    'attr' => array(
                        'placeholder' => 'Powtórz hasło',
                        'autocomplete' => 'off'
                    )
                )
            ));
        }
            $builder->add('accountExpired', 'checkbox', array(
                'label' => 'Konto wygasło',
                'attr' => array(
                    'class' => 'minimal'
                )
            ))
            ->add('accountLocked', 'checkbox', array(
                'label' => 'Konto zablokowane',
                'attr' => array(
                    'class' => 'minimal'
                )
            ))
            ->add('credentialsExpired', 'checkbox', array(
                'label' => 'Dane uwierzytelniające wygasły',
                'attr' => array(
                    'class' => 'minimal'
                )
            ))
            ->add('enabled', 'checkbox', array(
                'label' => 'Konto aktywowane',
                'attr' => array(
                    'class' => 'minimal'
                )
            ))
            ->add('roles', 'choice', array(
                'label' => 'Uprawnienia',
                'multiple' => true,
                'choices' => array(
                    'ROLE_USER' => 'Użytkownik',
                    'ROLE_EDITOR' => 'Redaktor',
                    'ROLE_ADMIN' => 'Administrator',
                    'ROLE_SUPER_ADMIN' => 'Super Administrator'
                ),
                'attr' => array(
                    'class' => 'select2'
                )
            ))
            ->add('save', 'submit', array(
                'label' => 'Zapisz',
                'attr' => array(
                    'class' => 'btn btn-success'
                )
            ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Common\UserBundle\Entity\User',
            'register' => TRUE
        ));
    }

    
}