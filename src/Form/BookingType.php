<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('placesKid', NumberType::class,[
                'label' => 'Places enfants',
                'mapped' => false,
            ])
            ->add('placesAdult', NumberType::class,[
                'label' => 'Places adultes',
                'mapped' => false,
            ])
            ->add('placesSenior', NumberType::class,[
                'label' => 'Places seniors',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
