<?php

namespace App\Controller\Api\Trainer;

use App\Controller\AbstractController;
use App\Entity\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrainerApiController extends AbstractController
{
    /**
     * @Route("/api/trainer", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateInfo(Request $request)
    {
        try {
            $trainer = $this->getTrainer();

            $data = json_decode($request->getContent(), true);

            if (isset($data['personal_statement'])) {
                $trainer->setPersonalStatement($data['personal_statement']);
            }

            if (isset($data['phone'])) {
                $trainer->setPhone($data['phone']);
            }

            if (isset($data['location'])) {
                $trainer->setLocation($data['location']);
            }

            if (isset($data['tags'])) {
                $tagsRepository = $this->getDoctrine()->getRepository(Tag::class);

                $tags = $trainer->getTags();
                foreach ($tags as $tag) {
                    $trainer->removeTag($tag);
                    $this->getDoctrine()->getManager()->flush();
                }

                foreach ($data['tags'] as $tagId) {
                    $tag = $tagsRepository->find($tagId);
                    if ($tag) {
                        $trainer->addTag($tag);
                        $this->getDoctrine()->getManager()->flush();
                    }
                }
            }
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse($trainer);
        } catch (\Throwable $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }
}
