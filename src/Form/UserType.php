<?php
/**
 * Created by PhpStorm.
 * User: Ignas
 * Date: 12/20/2018
 * Time: 7:22 PM
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['class' => 'regInput']])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array(
                    'attr' => array('class' => 'password-field')
                ),
                'required' => true,
                'first_options' => ['label' => 'Password', 'attr' => [
                    'class' => 'regInput',
                    'minlength' => 6,
                ]],
                'second_options' => ['label' => 'Repeat password', 'attr' => [
                    'class' => 'regInput',
                    'minlength' => 6,
                ]],
            ));
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
