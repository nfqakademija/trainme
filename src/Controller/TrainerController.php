<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Trainer;
use App\Repository\TagRepository;
use App\Repository\TrainerRepository;
use App\ValueObjects\Filter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class TrainerController extends AbstractController
{
    /**
     * @Route("/trainers/list", name="list")
     * @param Request $request
     * @param TrainerRepository $trainerRepository
     * @param TagRepository $tagRepository
     * @return Response
     */
    public function index(TrainerRepository $trainerRepository, TagRepository $tagRepository, Request $request)
    {
        $filter = new Filter($request);

        $allTags = $tagRepository->findAll();
        $trainers = $trainerRepository->findFilteredTrainers($filter);
        $maxPages = ceil($trainers->count() / $filter->getItemsPerPage());

        return $this->render('trainer/list.html.twig', ['tags' => $allTags, 'trainers' => $trainers->getIterator(),
            'filter' => $filter, 'maxPages' => $maxPages]);
    }

    /**
     * @Route("/trainers/{trainer}", name="trainer_page")
     * @param Trainer $trainer
     * @param null|UserInterface $user
     * @param Request $request
     * @return Response
     */
    public function show(Trainer $trainer, ?UserInterface $user, Request $request)
    {
        $form = $this->buildForm(null);

        $tagRepository = $this->getDoctrine()->getRepository(Tag::class);

        return $this->render('trainer/trainer.html.twig', [
            'trainer' => $trainer,
            'user' => $user,
            'count' => count($trainer->getScheduledWorkouts()->getIterator()),
            'allTags' => $tagRepository->findAll(),
            'tags' => $trainer->getTags()->toArray(),
            'selectedDate' => $request->get('selected_date'),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("trainer/upload_image", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function uploadPhoto(Request $request)
    {
        try {
            $trainer = $this->getTrainer();
            $form = $this->buildForm($trainer);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
            }
            return new RedirectResponse('/trainers/' . $trainer->getId());
        } catch (\Exception $e) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @param $trainer
     * @return \Symfony\Component\Form\FormInterface
     */
    public function buildForm($trainer): \Symfony\Component\Form\FormInterface
    {
        $form = $this->createFormBuilder($trainer)
            ->setAction('/trainer/upload_image')
            ->add('imageFile', VichImageType::class, [
                'attr' => ['class' => 'fileInput']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn upload-submit'],
                'label' => 'Save'
            ])
            ->getForm();

        return $form;
    }
}
