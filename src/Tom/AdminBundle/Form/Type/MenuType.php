<?php

namespace Tom\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuType extends AbstractType {

    public function getName() {
        return 'menu_type';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $MenuId = $builder->getForm()->getData()->getId();
        
        if(NULL == $MenuId) {
            $MenuId = 0;
        }
        
        $builder
                ->add('title', 'text', array(
                    'label' => 'Tytuł',
                    'attr' => array(
                        'autocomplete' => 'off',
                    )
                ))
                ->add('route', 'choice', array(
                    'label' => 'Typ pozycji',
                    'choices' => array(
                        'tom_site_page' => 'Strona statyczna',
                        
                        'tom_site_articles' => 'Przegląd artykułów (wszystkich lub kategorii)',
                        'tom_site_article' => 'Pojedynczy artykuł',
                        
                        'tom_site_galleries' => 'Przegląd galerii zdjęć',
                        'tom_site_gallery' => 'Pojedyncza galeria zdjęć',
                        
                        'tom_site_events' => 'Przegląd nadchodzących wydarzeń',
                        'tom_site_event' => 'Pojedyncze wydarzenie',
                        
                        'user_accountSettings' => 'Panel użytkownika',
                        
                        'seperator' => 'Seperator',
                    ),
                    'attr' => array(
                        'onchange' => 'menuTypeParameter()'
                    )
                ))
                ->add('parent', 'entity', array(
                    'class' => 'TomSiteBundle:Menu',
                    'property' => 'title',
                    'query_builder' => function ($er) use($MenuId) {
                        return $er->createQueryBuilder('p')
                                ->select('p')
                                ->where('p.id != :id')
                                ->setParameter('id', $MenuId);
                    },
                    'empty_value' => '-- Pozycja główna --',
                    'empty_data' => NULL,
                    'label' => 'Pozycja macierzysta'
                ))
                ->add('sort', 'integer', array(
                    'label' => 'Kolejnosć',
                    'attr' => array(
                        'min' => '0',
                    )
                ))
                ->add('publishedDate', 'datetime', array(
                    'label' => 'Data publikacji',
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text'
                ))
                ->add('type', 'entity', array(
                    'label' => 'Pokaż w menu',
                    'class' => 'Tom\SiteBundle\Entity\MenuType',
                    'property' => 'name',
                    'empty_value' => 'Wybierz menu',
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 100%'
                    )
                ))
                ->add('save', 'submit', array(
                    'label' => 'Zapisz',
                    'attr' => array(
                        'class' => 'btn btn-success btn-fixed'
        )));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Tom\SiteBundle\Entity\Menu'
        ));
    }

}
