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
            ->add('nameSugestion', 'textarea', array(
                'label' => 'Nazwa',
            ))
            ->add('descriptionSugestion', 'textarea', array(
                'label' => 'Sugestia',
            ))
            ->add('updateDate', 'datetime', array(
                'label' => 'Data dodania',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ))
            ->add('readDate', 'datetime', array(
                 'label' => '',
                 'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                 'attr' => array(
                        'class' => 'hidden1')
            ))
            ->add('approvedDate', 'datetime', array(
                 'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'attr' => array(
                        'class' => 'hidden')
            ))
            ->add('notapprovedDate', 'datetime', array(
                 'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'attr' => array(
                        'class' => 'hidden')
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
