<?php

namespace App\Controller;

use App\Entity\Trainer;
use App\Repository\TagRepository;
use App\Repository\TrainerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function home()
    {
        return $this->render('home/home.html.twig');
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
    public function show(Trainer $trainer, ?UserInterface $user)
    {
        $count = count($trainer->getScheduledWorkouts()->getIterator());
        return $this->render('trainer/template.html.twig', compact('trainer', 'user', 'count'));
    }
}
