<?php

namespace Tom\AdminBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class MenuTypeDeleteType extends AbstractType {
    
    private $type;

    function __construct($type) {
        $this->type = $type;
    }

    public function getName() {
        return 'deleteMenuType';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $Type = $this->type;
        
        $builder
            ->add('setNull', 'checkbox', array(
                'label' => 'Ustaw wszystkie pozycje menu bez kategorii',
                'attr' => array(
                    'class' => 'minimal-red'
                ),
            ))
            ->add('newType', 'entity', array(
                'label' => 'Wybierz nowy typ menu',
                'empty_value' => 'Wybierz typ menu',
                'class' => 'Tom\SiteBundle\Entity\MenuType',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) use ($Type){
                    return $er->createQueryBuilder('t')
                                ->where('t.id != :typeId')
                                ->setParameter('typeId', $Type->getId());
                }
            ))
            ->add('submit', 'submit', array(
                    'label' => 'Usuń',
                    'attr' => array(
                        'class' => 'btn btn-danger btn-fixed'
                    ),
            ));
    }
}