<?php

namespace App\Form;

use App\Entity\Games;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class GamesTypeMaj extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mark')
            ->add('nom')
            ->add('isbn')
            ->add('age')
            ->add('img')

            ->add('img' , FileType::class,[
                'label' =>"chargez une autre image",
                'data_class' => null,
                'required' =>false
            ])

            ->add('txt')
            ->add('note')
            ->add('status')
            ->add('date')
            // ->add('idcat')
            ->add('idcat' ,EntityType::class,[
                'class' => Categories::class,
                'choice_label' => 'nom' ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Games::class,
        ]);
    }
}
