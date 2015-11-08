<?php

namespace Common\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RememberPasswordType extends AbstractType {
    
    public function getName() {
        return 'rememberPassword';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('email', 'email', array(
                    'label' => false,
                    'constraints' => array(
                        new Assert\Email(),
                        new Assert\NotBlank(),
                    ),
                    'attr' => array(
                        'placeholder' => 'Twój adres e-mail',
                        'autocomplete' => 'off'
                    )
                ))
                ->add('submit', 'submit', array(
                    'label' => 'Przypomnij hasło',
                    'attr' => array(
                        'class' => 'btn btn-primary btn-block btn-flat'
                    )
                ));
    }

}