<?php

namespace Tom\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class SeoType extends AbstractType{
    
    public function getName() {
        return 'seo_form';
    }    
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
            ->add('descriptions', 'text', array(
                'label' => 'Meta description',
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('keywords', 'text', array(
                'label' => 'Meta keywords',
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('h1_index', 'text', array(
                'label' => 'Nagłówek H1',
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
            'data_class' => 'Tom\SiteBundle\Entity\Seo'
        ));
    }
}
