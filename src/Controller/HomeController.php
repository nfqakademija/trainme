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
     * @Route("/trainers/list", name="home")
     * @param Request $request
     * @param TrainerRepository $trainerRepository
     * @return Response
     */
    public function index(TrainerRepository $trainerRepository, Request $request)
    {
        $name = $request->query->get('name');
        $date = $request->query->get('date');
        $from = $request->query->get('from');
        $to = $request->query->get('to');
        $page = $request->query->get('page') ?? 1;

        $startsAt = null;
        $endsAt = null;


        if ($date && $from && $to) {
            $date = new \DateTime($date);
            $from = new \DateTime(str_replace(' ', '', $from));
            $to = new \DateTime(str_replace(' ', '', $to));
            $startsAt = new \DateTime($date->format('Y-m-d') . ' ' . $from->format('H:i:s'));
            $endsAt = new \DateTime($date->format('Y-m-d') . ' ' . $to->format('H:i:s'));
        }

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
