<?php

namespace App\Form;

use App\Entity\Trainer;
use App\Repository\TagRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
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
            ->add('name')
            ->add('phone', TelType::class)
            ->add('location')
            ->add('personalStatement')
            ->add('image_url', UrlType::class)
            ->add('tags', ChoiceType::class, [
                'choices' => $this->tagRepository->findAll(),
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
