<?php


namespace App\Form;

use App\Model\User\UserData;
use App\Model\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => 'Meno'
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => 'Priezvisko'
            ])
            ->add('submit',SubmitType::class, [
                'label' =>'Uložiť',

            ])
            ;
        
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['user'])
            ->setAllowedTypes('user', [User::class, 'null'])
            ->setDefaults([
                'data_class' => UserData::class,
            ]);
    }
}