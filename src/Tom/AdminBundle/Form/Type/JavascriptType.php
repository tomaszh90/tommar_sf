<?php

namespace Tom\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class JavascriptType extends AbstractType{
    
    public function getName() {
        return 'script_form';
    }    
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
            ->add('script_head', 'text', array(
                'label' => 'Javascript head',
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('script_bottom', 'text', array(
                'label' => 'Javascript bottom',
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('submit', 'submit', array(
                'label' => 'Zapisz',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
                
            ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Tom\SiteBundle\Entity\Javascript'
        ));
    }
}
