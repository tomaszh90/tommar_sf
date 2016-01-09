<?php

namespace Tom\SiteBundle\Form\Type;

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
            ->add('nameSugestion', 'text', array(
                'label' => 'Nazwa',
            ))
            ->add('descriptionSugestion', 'textarea', array(
                'label' => 'Sugestia',
            ))
            ->add('emailSugestion', 'text', array(
                'label' => 'email',
            ))
             ->add('save', 'submit', array(
                    'label' => 'Zapisz',
                    'attr' => array(
                        'class' => 'btn btn-success')
             ));
    }
    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tom\SiteBundle\Entity\Sugestion'
        ));
    }

}
