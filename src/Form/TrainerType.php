<?php

namespace App\Form;

use App\Entity\Trainer;
use App\Repository\TagRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Tag;

class TrainerType extends AbstractType
{
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

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
            ->add('image_url', UrlType::class, [
                'attr' => ['class' => 'regInput', 'autocomplete' => 'off'],
                'label_attr' => ['class' => 'regLabel']
            ])
            ->add('tags', ChoiceType::class, [
                'choices' => $this->tagRepository->findAll(),
                'label_attr' => ['class' => 'regLabel'],
                'attr' => ['class' => 'choiceInput'],
                'choice_label' => function (Tag $tag) {
                    return $tag->getName();
                },
                'multiple' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trainer::class
        ]);
    }
}
