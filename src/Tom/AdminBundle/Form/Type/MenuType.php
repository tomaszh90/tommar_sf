<?php

namespace Tom\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MenuType extends AbstractType {

    public function getName() {
        return 'menu_type';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title', 'text', array(
                    'label' => 'Nazwa',
                    'attr' => array(
                        'autocomplete' => 'off',
                    )
                ))
                ->add('route', 'choice', array(
                    'label' => 'Rodzaj pozycji',
                    'choices' => array(
                        'tom_site_articles' => 'Przegląd artykułów',
                        'tom_site_article' => 'Pojedynczy artykuł',
                    ),
                    'attr' => array(
                        'onchange' => 'menuTypeParameter()'
                    )
                ))
                ->add('publishedDate', 'datetime', array(
                    'label' => 'Data publikacji',
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text'
                ))
                ->add('type', 'entity', array(
                    'label' => 'Typ menu',
                    'class' => 'Tom\SiteBundle\Entity\MenuType',
                    'property' => 'name',
                    'empty_value' => 'Wybierz typ menu',
                    'attr' => array(
                        'class' => 'select2',
                        'style' => 'width: 100%'
                    )
                ))
                ->add('save', 'submit', array(
                    'label' => 'Zapisz',
                    'attr' => array(
                        'class' => 'btn btn-success'
        )));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Tom\SiteBundle\Entity\Menu'
        ));
    }

}
