<?php

namespace Tom\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
                    {
        $builder->add('descriptions', 'text');
        $builder->add('keywords', 'text');
        $builder->add('h1_index','text');
        //$builder->add('created', 'date');
        //$builder->add('body','textarea');
        
        
    }
    public function getName() {
        return 'create';
    }

}