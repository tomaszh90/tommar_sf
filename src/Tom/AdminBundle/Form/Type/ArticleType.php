<?php

namespace Tom\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{

    public function getName()
    {
        return 'article';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'Tytuł',
                'attr' => array(
                    'placeholder' => 'Tytuł'
                )
            ))
            ->add('slug', 'text', array(
                'label' => 'Alias',
                'attr' => array(
                    'placeholder' => 'Alias'
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
                'class' => 'Tom\SiteBundle\Entity\ArticleCategory',
                'property' => 'name',
                'empty_value' => 'Wybierz kategorię'
            ))
            ->add('tags', 'entity', array(
                'label' => 'Tagi',
                'multiple' => true,
                'class' => 'Tom\SiteBundle\Entity\Tag',
                'property' => 'name',
                'attr' => array(
                    'placeholder' => 'Dodaj tagi'
                )
            ))
            ->add('save', 'submit', array(
                'label' => 'Zapisz'
            ));
    }
    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tom\SiteBundle\Entity\Article'
        ));
    }

}
