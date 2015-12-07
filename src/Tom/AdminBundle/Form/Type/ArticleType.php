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
                    'autocomplete' => 'off',
                )
            ))
            ->add('slug', 'text', array(
                'label' => 'Alias',
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('source', 'text', array(
                'label' => 'Źródło',
                'attr' => array(
                    'autocomplete' => 'off'
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
                'empty_value' => 'Wybierz kategorię',
                'attr' => array(
                    'class'       => 'select2',
                    'style'       => 'width: 100%'
                )
            ))
            ->add('tags', 'entity', array(
                'label' => 'Tagi',
                'multiple' => true,
                'class' => 'Tom\SiteBundle\Entity\Tag',
                'property' => 'name',
                'attr' => array(
                    'placeholder' => 'Dodaj tagi',
                    'class'       => 'select2',
                    'style'       => 'width: 100%'
                )
            ))
            ->add('newTag', 'text', array(
                'label' => 'Dodaj tag',
                'mapped' => false,
                'attr' => array(
                    'autocomplete' => 'off',
                    'ng-model' => 'article.addTag',
                    'ng-minlength' => '3',
                    'ng-maxlength' => '60',
                    'ng-pattern' => '/^[\w -]*$/',
                    'required' => '',
                    'placeholder' => 'Wprowadź nowy tag'
                )
            ))
            ->add('saveTag', 'button', array(
                'label' => 'Dodaj',
                'attr' => array(
                    'class' => 'btn btn-primary',
                    'ng-click' => 'addTag(article.addTag)',
                    'ng-disabled' => '!article.addTag'
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
            'data_class' => 'Tom\SiteBundle\Entity\Article'
        ));
    }

}
