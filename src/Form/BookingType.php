<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('placesKid', IntegerType::class,[
                'label' => 'Places enfants',
                'mapped' => false,
                'attr' => [
                    'min' => 0,
                    'max' => 10,
                    'v-model.number' => 'plk'
                ],
            ])
            ->add('placesAdult', IntegerType::class,[
                'label' => 'Places adultes',
                'mapped' => false,
                'attr' => [
                    'min' => 0,
                    'max' => 10,
                    'v-model.number' => 'pla'

                ],
            ])
            ->add('placesSenior', IntegerType::class,[
                'label' => 'Places seniors',
                'mapped' => false,
                'attr' => [
                    'min' => 0,
                    'max' => 10,
                    'v-model.number' => 'pls'

                ],
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
