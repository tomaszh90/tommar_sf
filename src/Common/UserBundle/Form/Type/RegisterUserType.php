<?php
namespace Common\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RegisterUserType extends AbstractType{
    
    public function getName() {
        return 'userRegister';
    }    
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
            ->add('email', 'email', array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Adres e-mail',
                    'autocomplete' => 'off'
                )
            ))
            ->add('username', 'text', array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Nazwa użytkownika',
                    'autocomplete' => 'off'
                )
            ))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'first_options' => array(
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Hasło',
                        'autocomplete' => 'off'
                    )
                ),
                'second_options' => array(
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Powtórz hasło',
                        'autocomplete' => 'off'
                    )
                )
            ))
            ->add('submit', 'submit', array(
                'label' => 'Zarejestruj',
                'attr' => array(
                    'class' => 'btn btn-primary btn-block btn-flat'
                )
                
            ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Common\UserBundle\Entity\User',
            'validation_groups' => array('Default', 'Registration')
        ));
    }
}
