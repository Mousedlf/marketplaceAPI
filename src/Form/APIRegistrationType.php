<?php

namespace App\Form;

use App\Entity\API;
use App\Entity\Order;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class APIRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('clientCreationRoute')
            ->add('baseUrl')
            ->add('getRequestsRoute')
            ->add('revokeKeyRoute')
            ->add('generateNewKey')
            ->add('addNewRequestsRoute')


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => API::class,
        ]);
    }
}
