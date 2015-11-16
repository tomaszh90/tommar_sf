<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MessengerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contents', 'textarea')
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

        });
    }

    public function getName()
    {
        return 'messenger';
    }
}