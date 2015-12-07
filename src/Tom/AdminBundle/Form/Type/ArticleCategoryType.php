<?php

namespace Tom\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ArticleCategoryType extends AbstractType
{

    public function getName()
    {
        return 'articleCategory';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Nazwa',
                'attr' => array(
                    'autocomplete' => 'off',
                )
            ))
            ->add('slug', 'text', array(
                'label' => 'Alias',
                'attr' => array(
                    'autocomplete' => 'off',
                )
            ))
            ->add('imageFile', 'file', array(
                'label' => 'Zdjęcie'
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
            'data_class' => 'Tom\SiteBundle\Entity\ArticleCategory'
        ));
    }
}
