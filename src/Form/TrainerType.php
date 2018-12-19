<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Trainer;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class TrainerType
 * @package App\Form
 */
class TrainerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'regInput', 'autocomplete' => 'off']
            ])
            ->add('phone', TelType::class, [
                'attr' => ['class' => 'regInput', 'autocomplete' => 'off']
            ])
            ->add('location', TextType::class, [
                'attr' => ['class' => 'regInput', 'autocomplete' => 'off']
            ])
            ->add('personalStatement', TextareaType::class, [
                'attr' => ['class' => 'regTextArea', 'autocomplete' => 'off'],
                'label_attr' => ['class' => 'u-mgTop']
            ])
            ->add('imageFile', VichImageType::class, [
                'attr' => ['class' => 'regInput imageUpload', 'autocomplete' => 'off'],
                'label_attr' => ['class' => 'regLabel']
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'label_attr' => ['class' => 'regLabel'],
                'attr' => ['class' => 'choiceInput'],
                'choice_label' => 'name',
                'multiple' => true]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trainer::class
        ]);
    }
}
