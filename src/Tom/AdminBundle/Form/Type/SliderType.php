<?php

namespace Tom\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SliderType extends AbstractType
{

    public function getName()
    {
        return 'slider';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'Nazwa',
                'attr' => array(
                    'autocomplete' => 'off',
                )
            ))
            ->add('content', 'textarea', array(
                'label' => 'Treść',
                'attr' => array(
                    'class' => 'tinymce'
                )
            ))
            ->add('imageFile', 'file', array(
                'label' => 'Zdjęcie'
            ))
            ->add('publishedDate', 'datetime', array(
                'label' => 'Data publikacji',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ))
            ->add('category', 'entity', array(
                'label' => 'Kategoria',
                'class' => 'Tom\SiteBundle\Entity\SliderCategory',
                'property' => 'name',
                'empty_value' => 'Wybierz kategorię',
                'attr' => array(
                    'class'       => 'select2',
                    'style'       => 'width: 100%'
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
            'data_class' => 'Tom\SiteBundle\Entity\Slider'
        ));
    }

}
