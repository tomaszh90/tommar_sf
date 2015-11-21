<?php //
namespace Tom\AdminBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
 
class MessengerType extends AbstractType
{
  public function buildForm( FormBuilderInterface $builder, array $options) {
  
    $builder->add( 'contents', 'text', array(
                'label' => 'Treść:',
                 'attr' => array(
                    'class' => 'form-control',
                     'onclick' => 'myFunction()',
                     'placeholder' => 'Kliknij, aby włączyć czat'
                )
            )); 
    //$builder->add( 'body',  'textarea' );
  }
 
  function getName() {
    return 'MessengerType';
  }
  public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tom\SiteBundle\Entity\Messenger'
        ));
    }
}
