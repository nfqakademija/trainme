<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CustomerRegistrationType
 * @package App\Form
 */
class CustomerRegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['class' => 'regInput']])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field regInput')),
                'required' => true,
                'first_options' => ['label' => 'Password', 'attr' => [
                    'class' => 'regInput',
                    'minlength' => 5
                ]],
                'second_options' => ['label' => 'Repeat password', 'attr' => [
                    'class' => 'regInput',
                    'minlength' => 5
                ]],
            ))
            ->add('personal_info', CustomerType::class, array(
                'label' => 'Personal info:',
                'label_attr' => [
                    'class' => 'regSecLabel'
                ]
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'Register',
                'attr' => ['class' => 'btnPrimary u-mgTop']
            ]);
    }
}
