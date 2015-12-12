<?php

namespace Tom\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SugestionType extends AbstractType
{

    public function getName()
    {
        return 'sugestion';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'text', array(
                'label' => 'Id',
                'attr' => array(
                    'autocomplete' => 'off',
                )
            ))
            ->add('name_sugestion', 'textarea', array(
                'label' => 'Nazwa',
            ));
            
    }
    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tom\SiteBundle\Entity\Sugestion'
        ));
    }

}
