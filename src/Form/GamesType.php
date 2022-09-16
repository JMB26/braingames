<?php

namespace App\Form;

use App\Entity\Games;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class GamesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mark')
            ->add('nom')
            ->add('isbn')
            ->add('age', ChoiceType::class, array(
                'choices' => array(
                    '3' => 3,
                    '7' => 7,
                    '12' => 12,
                    '16' => 16,
                    '18' => 18,
                )
            ))          

            ->add('img', FileType::class, [
                'label' => "chargez une image",
                'data_class' => null,
                'required' => false
            ])

            ->add('txt')

            ->add('note', HiddenType::class, [
                'data' => '0',
            ])

            ->add('status', HiddenType::class, [
                'data' => '0',
            ])
                      
            ->add('date', DateTimeType::class, array(
                'data' => new \DateTime('now'),
                'attr'=>array('style'=>'display:none;'),
                'label' => false
            ))
           
            ->add('idcat', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'nom'
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Games::class,
        ]);
    }
}
