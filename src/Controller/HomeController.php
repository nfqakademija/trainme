<?php

namespace App\Controller;

use App\Entity\Trainer;
use App\Repository\TrainerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/trainers/list/{page}", name="home")
     * @param Request $request
     * @param TrainerRepository $trainerRepository
     * @param int $page
     * @return Response
     */
    public function index(TrainerRepository $trainerRepository, Request $request, $page = 1)
    {
        $name = $request->get('name');
        $date = $request->get('date');
        $from = $request->get('from');
        $to = $request->get('to');

        $startsAt = new \DateTime($date . ' ' . $from);
        $endsAt = new \DateTime(($date . ' ' . $to));

        $trainers = $trainerRepository->findFilteredTrainers($page, 5, $name, $startsAt, $endsAt, []);
        $maxPages = ceil($trainers->count() / 5);

        return $this->render('home/index.html.twig', ['trainers' => $trainers->getIterator(), 'thisPage' => $page, 'maxPages' => $maxPages]);
    }

    /**
     * @Route("/trainers/{trainer}")
     * @param Trainer $trainer
     * @return Response
     */
    public function show(Trainer $trainer)
    {
        return $this->render('trainer/trainer.html.twig', compact('trainer'));
    }
}
