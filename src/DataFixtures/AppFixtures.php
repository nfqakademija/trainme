<?php

namespace App\DataFixtures;

use App\Entity\AvailabilitySlot;
use App\Entity\ScheduledWorkout;
use App\Entity\Tag;
use App\Entity\Trainer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $availabilitySlots = [
            [
                'from' => '2018-11-10 10:00:00',
                'to' => '2018-11-10 12:00:00',
                'trainerId' => 1
            ],
            [
                'from' => '2018-11-10 14:00:00',
                'to' => '2018-11-10 16:00:00',
                'trainerId' => 1
            ],
            [
                'from' => '2018-11-10 13:00:00',
                'to' => '2018-11-10 15:00:00',
                'trainerId' => 2
            ],
            [
                'from' => '2018-11-10 09:00:00',
                'to' => '2018-11-10 17:00:00',
                'trainerId' => 3
            ]
        ];

        $scheduledWorkouts = [
            [
                'from' => '2018-11-10 10:00:00',
                'to' => '2018-11-10 10:30:00',
                'trainerId' => 1
            ],
            [
                'from' => '2018-11-10 11:00:00',
                'to' => '2018-11-10 12:00:00',
                'trainerId' => 1
            ],
            [
                'from' => '2018-11-10 14:00:00',
                'to' => '2018-11-10 15:00:00',
                'trainerId' => 1
            ],
            [
                'from' => '2018-11-10 15:00:00',
                'to' => '2018-11-10 16:00:00',
                'trainerId' => 1
            ],
            [
                'from' => '2018-11-10 12:00:00',
                'to' => '2018-11-10 13:00:00',
                'trainerId' => 2
            ],
            [
                'from' => '2018-11-10 10:00:00',
                'to' => '2018-11-10 14:00:00',
                'trainerId' => 3
            ],
            [
                'from' => '2018-11-10 14:00:00',
                'to' => '2018-11-10 16:00:00',
                'trainerId' => 3
            ]
        ];
        $tags = [
            'Indoors' => 'Tag 1 description',
            'Outdoors' => 'Tag 2 description',
            'Yoga' => 'Tag 3 description'
        ];

        $tagObjects = [];

        foreach ($tags as $name => $description) {
            $tag = new Tag();
            $tag->setName($name);
            $tag->setDescription($description);
            $tagObjects[] = $tag;
            $manager->persist($tag);
        }

        for ($i = 0; $i < 20; $i++) {
            $trainer = new Trainer();
            $trainer->setName(sprintf("John Doe - %s", $i));
            $trainer->setPersonalStatement("personal statment");
            $trainer->setPhone("8686868686");
            $trainer->setImageUrl("https://placeimg.com/350/250/animals");
            $this->addReference(sprintf("Trainer %s", $i + 1), $trainer);
            $manager->persist($trainer);
        }

        $trainer1 = $this->getReference(sprintf("Trainer %s", 1));
        $trainer2 = $this->getReference(sprintf("Trainer %s", 4));
        $trainer3 = $this->getReference(sprintf("Trainer %s", 8));

        $trainer1->addTag($tagObjects[0]);
        $trainer1->addTag($tagObjects[2]);
        $trainer2->addTag($tagObjects[1]);
        $trainer3->addTag($tagObjects[0]);
        $trainer3->addTag($tagObjects[1]);
        $trainer3->addTag($tagObjects[2]);


        foreach ($availabilitySlots as $availabSl) {
            $availabilitySlot = new AvailabilitySlot();
            $availabilitySlot->setStartsAt(new \DateTime($availabSl['from']));
            $availabilitySlot->setEndsAt(new \DateTime($availabSl['to']));

            $availabilitySlot->setTrainer($this->getReference(sprintf("Trainer %s", $availabSl['trainerId'])));
            $manager->persist($availabilitySlot);
        }

        foreach ($scheduledWorkouts as $schedWork) {
            $scheduledWorkout = new ScheduledWorkout();
            $scheduledWorkout->setStartsAt(new \DateTime($schedWork['from']));
            $scheduledWorkout->setEndsAt(new \DateTime($schedWork['to']));
            $scheduledWorkout->setTrainer($this->getReference(sprintf("Trainer %s", $schedWork['trainerId'])));
            $manager->persist($scheduledWorkout);
        }

        $manager->flush();
    }
}
