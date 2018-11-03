<?php

namespace App\Form;

use App\Entity\RepLog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepLogType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options){
    $builder
        ->add('reps')
        ->add('item', ChoiceType::class,[
	        'choices' => RepLog::getThingsYouCanLiftChoices(),
	        'placeholder' => 'What did you lift?'
        ]);
    ;
  }

  public function configureOptions(OptionsResolver $resolver){
    $resolver->setDefaults([
      'data_class' => RepLog::class,
    ]);
  }
}
