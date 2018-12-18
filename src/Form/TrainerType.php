<?php

namespace App\Form;

use App\Entity\Trainer;
use App\Repository\TagRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Tag;

/**
 * Class TrainerType
 * @package App\Form
 */
class TrainerType extends AbstractType
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * TrainerType constructor.
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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
            ->add('tags', ChoiceType::class, [
                'choices' => $this->tagRepository->findAll(),
                'label_attr' => ['class' => 'regLabel'],
                'attr' => ['class' => 'choiceInput'],
                'choice_label' => function (Tag $tag) {
                    return $tag->getName();
                },
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
