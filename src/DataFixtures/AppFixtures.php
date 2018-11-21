<?php

namespace App\DataFixtures;

use App\Entity\AvailabilitySlot;
use App\Entity\ScheduledWorkout;
use App\Entity\Tag;
use App\Entity\Trainer;
use function Couchbase\fastlzCompress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
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
            'Yoga' => 'Tag 3 description',
            'Weight lifting' => 'Tag 4 description',
            'Running' => 'Tag 5 description',
            'Endurance training' => 'Tag 6 description',
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
            $trainer->setName($faker->name);
            $trainer->setPersonalStatement($faker->realText());
            $trainer->setPhone($faker->phoneNumber);
            $trainer->setImageUrl($faker->imageUrl(250, 250, 'sports', false, $i % 10 + 1));
            $this->addReference(sprintf("Trainer %s", $i + 1), $trainer);
            $trainer->addTag($tagObjects[$faker->numberBetween(0, count($tags)-1)]);
            $manager->persist($trainer);
        }





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
