<?php //
namespace Tom\AdminBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class MessengerType extends AbstractType
{
  public function buildForm( FormBuilderInterface $builder,
                                            array $options )
  {
    $builder->add( 'contents', 'text' );
    //$builder->add( 'body',  'textarea' );
  }
 
  function getName() {
    return 'MessengerType';
  }
}
