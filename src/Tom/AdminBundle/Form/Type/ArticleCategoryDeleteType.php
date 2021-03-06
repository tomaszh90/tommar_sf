<?php

namespace Tom\AdminBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class ArticleCategoryDeleteType extends AbstractType {
    
    private $category;

    function __construct($category) {
        $this->category = $category;
    }

    public function getName() {
        return 'deleteArticleCategory';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $Category = $this->category;
        
        $builder
            ->add('setNull', 'checkbox', array(
                'label' => 'Ustaw wszystkie artykuły bez kategorii',
                'attr' => array(
                    'class' => 'minimal-red'
                ),
            ))
            ->add('newCategory', 'entity', array(
                'label' => 'Wybierz nową kategorię',
                'empty_value' => 'Wybierz kategorię',
                'class' => 'Tom\SiteBundle\Entity\ArticleCategory',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) use ($Category){
                    return $er->createQueryBuilder('c')
                                ->where('c.id != :categoryId')
                                ->setParameter('categoryId', $Category->getId());
                }
            ))
            ->add('submit', 'submit', array(
                    'label' => 'Usuń',
                    'attr' => array(
                        'class' => 'btn btn-danger'
                    ),
            ));
    }
}
