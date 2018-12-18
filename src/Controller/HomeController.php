<?php

namespace App\Controller;

use App\Entity\Trainer;
use App\Entity\User;
use App\Form\TrainerImageUploadType;
use App\Repository\TagRepository;
use App\Repository\TrainerRepository;
use App\Services\TrainerEvaluationsService;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     * @param TrainerEvaluationsService $trainerEvaluationsService
     * @return Response
     */
    public function home(TrainerEvaluationsService $trainerEvaluationsService)
    {
        $trainerToEvaluate = null;

        $user = $this->getUser();
        if ($user instanceof User) {
            if ($this->isGranted('ROLE_CUSTOMER') && $user->getCustomer()) {
                $customer = $user->getCustomer();
                $didEvaluateTrainer = $customer->getHasEvaluatedTrainerOnLogin();

                if (!$didEvaluateTrainer) {
                    $trainerToEvaluate = $trainerEvaluationsService->getTrainerForEvaluation($customer);
                }
            }
        }
        return $this->render('home/home.html.twig', ['trainerToEvaluate' => $trainerToEvaluate]);
    }

    /**
     * @Route("/trainers/list", name="list")
     * @param Request $request
     * @param TrainerRepository $trainerRepository
     * @param TagRepository $tagRepository
     * @return Response
     */
    public function index(TrainerRepository $trainerRepository, TagRepository $tagRepository, Request $request)
    {
        $name = $request->query->get('name');
        $date = $request->query->get('date');
        $from = $request->query->get('from');
        $to = $request->query->get('to');
        $page = $request->query->get('page') ?? 1;
        $tags = $request->query->get('tags');

        $startsAt = null;
        $endsAt = null;

        if (!$tags) {
            $tags = [];
        }

        if ($date && $from && $to) {
            $date = new \DateTime($date);
            $from = new \DateTime(str_replace(' ', '', $from));
            $to = new \DateTime(str_replace(' ', '', $to));
            $startsAt = new \DateTime($date->format('Y-m-d') . ' ' . $from->format('H:i:s'));
            $endsAt = new \DateTime($date->format('Y-m-d') . ' ' . $to->format('H:i:s'));
        }

        $allTags = $tagRepository->findAll();
        $trainers = $trainerRepository->findFilteredTrainers($page, 6, $name, $startsAt, $endsAt, $tags);
        $maxPages = ceil($trainers->count() / 6);

        return $this->render('trainer/list.html.twig', ['tags' => $allTags, 'trainers' => $trainers->getIterator(),
            'thisPage' => $page, 'maxPages' => $maxPages]);
    }

    /**
     * @Route("/trainers/{trainer}", name="trainer_page")
     * @param Trainer $trainer
     * @param null|UserInterface $user
     * @return Response
     */
    public function show(Trainer $trainer, ?UserInterface $user, TagRepository $tagRepository)
    {
        $form = $this->buildForm(null);

        return $this->render('trainer/trainer.html.twig', [
            'trainer' => $trainer,
            'user' => $user,
            'count' => count($trainer->getScheduledWorkouts()->getIterator()),
            'all_tags' => $tagRepository->findAll(),
            'tags' => $trainer->getTags()->toArray(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("trainer/upload_image", methods={"POST"})
     */
    public function uploadPhoto(Request $request, UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }
        $trainer = $user->getTrainer();
        $form = $this->buildForm($trainer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new RedirectResponse('/trainers/' . $trainer->getId());
        }
        die;
    }

    /**
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
                'attr' => ['class' => 'upload__submit'],
                'label' => 'Save'
            ])
            ->getForm();

        return $form;
    }
}
