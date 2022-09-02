<?php

namespace App\Form;

use App\Entity\Swap;
use App\Entity\User;
use App\Entity\Games;
use App\Entity\Shape;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SwapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
        $builder
            ->add('swapuser')
            ->add('swapbuyer')           

            ->add('idshape' ,EntityType::class,[
                'class' => Shape::class,
                'choice_label' => 'id' ])        
            
            ->add('iduser' ,EntityType::class,[
                'class' => User::class,
                'choice_label' => 'id' ])     

            ->add('idbuyer' ,EntityType::class,[
                'class' => User::class,
                'choice_label' => 'id' ])    
           
            ->add('idgameuser' ,EntityType::class,[
                'class' => Games::class,
                'choice_label' => 'id' ])    

            ->add('idgamebuyer' ,EntityType::class,[
                'class' => Games::class,
                'choice_label' => 'id' ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Swap::class,
        ]);
    }
}
