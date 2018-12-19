<?php

namespace App\DataFixtures;

use App\Entity\AvailabilitySlot;
use App\Entity\Customer;
use App\Entity\ScheduledWorkout;
use App\Entity\Tag;
use App\Entity\Trainer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();


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
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $user->setRoles(['ROLE_TRAINER']);
            $manager->persist($user);
            $trainer->setName($faker->name);
            $trainer->setPersonalStatement($faker->realText());
            $trainer->setPhone($faker->phoneNumber);
            $trainer->setLocation($faker->city);
            $trainer->setImageName($faker->imageUrl(250, 250, 'sports', false, $i % 10 + 1));
            $this->addReference(sprintf("Trainer %s", $i + 1), $trainer);
            $trainer->addTag($tagObjects[$faker->numberBetween(0, count($tags) - 1)]);
            $trainer->setUser($user);
            $manager->persist($trainer);

            for ($day = 8; $day < 30; $day++) {
                $availabilitySlot = new AvailabilitySlot();

                $dateFrom = new \DateTime();
                $dateTo = new \DateTime();

                $dateFrom->setDate(2018, 12, $day);
                $dateTo->setDate(2018, 12, $day);
                $dateFrom->setTime(12, 0, 0);
                $dateTo->setTime(13, 0, 0);


                $availabilitySlot->setStartsAt($dateFrom);
                $availabilitySlot->setEndsAt($dateTo);
                $availabilitySlot->setTrainer($trainer);

                $customer = new Customer();
                $user = new User();

                $user->setEmail($faker->email);
                $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
                $user->setRoles(['ROLE_CUSTOMER']);
                $manager->persist($user);
                $customer->setUser($user);
                $customer->setPhone($faker->phoneNumber);
                $customer->setName($faker->name);
                $manager->persist($customer);

                $scheduledWorkout = new ScheduledWorkout();
                $scheduledWorkout->setTrainer($trainer);
                $scheduledWorkout->setCustomer($customer);

                if ($day % 2 == 0) {
                    $scheduledWorkout->setStartsAt(clone $availabilitySlot->getStartsAt());
                    $scheduledWorkout->setEndsAt(clone $availabilitySlot->getEndsAt());
                } else {
                    $newStartDate = $availabilitySlot->getStartsAt();
                    $newEndDate =  $availabilitySlot->getEndsAt();

                    if ($i % 2 == 0) {
                        $newStartDate->modify('+30 minutes');
                    } else {
                        $newEndDate->modify('-30 minutes');
                    }

                    $scheduledWorkout->setStartsAt(clone $newStartDate);
                    $scheduledWorkout->setEndsAt(clone $newEndDate);
                }

                $manager->persist($scheduledWorkout);
                $manager->persist($availabilitySlot);

                $date = new \DateTime();

                $date->setDate(2018, 12, $day);

                $availabilitySlot = new AvailabilitySlot();

                if ($i % 2 == 0) {
                    $date->setTime(8, 30, 0);
                } else {
                    $date->setTime(15, 00, 0);
                }

                $availabilitySlot->setStartsAt(clone $date);

                if ($i % 2 == 0) {
                    $date->setTime(11, 0, 0);
                } else {
                    $date->setTime(16, 45, 0);
                }

                $availabilitySlot->setEndsAt(clone $date);

                $availabilitySlot->setTrainer($trainer);

                if ($day % 4 == 0) {
                    $scheduledWorkout = new ScheduledWorkout();
                    $scheduledWorkout->setTrainer($trainer);
                    $scheduledWorkout->setCustomer($customer);

                    if ($i % 3 == 0) {
                        $scheduledWorkout->setStartsAt(clone $availabilitySlot->getStartsAt());
                        $scheduledWorkout->setEndsAt(clone $availabilitySlot->getEndsAt());
                    } else {
                        $newStartDate = clone $availabilitySlot->getStartsAt();
                        $newEndDate = clone $availabilitySlot->getEndsAt();

                        if ($i % 2 == 0) {
                            $newStartDate->modify('+15 minutes');
                        } else {
                            $newEndDate->modify('+20 minutes');
                            $newEndDate->modify('-10 minutes');
                        }

                        $scheduledWorkout->setStartsAt(clone $newStartDate);
                        $scheduledWorkout->setEndsAt(clone $newEndDate);
                    }

                    $manager->persist($scheduledWorkout);
                }
                $manager->persist($availabilitySlot);
            }
        }

        $manager->flush();
    }
}
