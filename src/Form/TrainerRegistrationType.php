<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TrainerRegistrationType
 * @package App\Form
 */
class TrainerRegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'regInput'],
                'label_attr' => ['class' => 'regLabel']
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options' => array(
                    'label' => 'Password',
                    'attr' => ['class' => 'regInput', 'minlength' => 5, 'maxlength' => 20]
                ),
                'second_options' => array(
                    'label' => 'Repeat Password',
                    'attr' => ['class' => 'regInput', 'minlength' => 5, 'maxlength' => 20]
                ),
            ))
            ->add('personal_info', TrainerType::class, [
                'label' => 'Personal info:',
                'label_attr' => ['class' => 'regSecLabel']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Register',
                'attr' => ['class' => 'btnPrimary']
            ]);
    }
}
