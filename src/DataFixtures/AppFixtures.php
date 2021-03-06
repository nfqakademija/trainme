<?php

namespace App\DataFixtures;

use App\Entity\AvailabilitySlot;
use App\Entity\Customer;
use App\Entity\Rating;
use App\Entity\ScheduledWorkout;
use App\Entity\Tag;
use App\Entity\Trainer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
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

    private $kernel;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param KernelInterface $kernel
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, KernelInterface $kernel)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->kernel = $kernel;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $imageNames = $this->getImageNames();

        $tags = [
            'Indoors', 'Outdoors', 'Yoga', 'Weight lifting', 'Running', 'Endurance', 'Crossfit', 'Weight loss',
            'Cardio', 'Fighting', 'Powerlifting', 'Flexibility'
        ];

        $tagObjects = [];

        foreach ($tags as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $tagObjects[] = $tag;
            $manager->persist($tag);
        }

        $dummyCustomer = new Customer();
        $user = new User();

        $user->setEmail($faker->email);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRoles(['ROLE_CUSTOMER']);
        $manager->persist($user);
        $dummyCustomer->setUser($user);
        $dummyCustomer->setPhone($faker->phoneNumber);
        $dummyCustomer->setName($faker->name);
        $manager->persist($dummyCustomer);

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
            $trainer->setImageName($imageNames[array_rand($imageNames)]);
            $this->addReference(sprintf("Trainer %s", $i + 1), $trainer);
            $trainer->addTag($tagObjects[$faker->numberBetween(0, count($tags) - 1)]);
            $trainer->setUser($user);
            $manager->persist($trainer);

            $rating = new Rating();
            $rating->setCustomer($dummyCustomer);
            $rating->setTrainer($trainer);
            $rating->setStars(rand(3, 5));
            $manager->persist($rating);


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
                    $newEndDate = $availabilitySlot->getEndsAt();

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

        $userTrainer = new User();
        $trainer = new Trainer();
        $userTrainer->setEmail('trainer123@train.me');
        $userTrainer->setPassword($this->passwordEncoder->encodePassword($userTrainer, 'trainer123'));
        $userTrainer->setRoles([User::ROLE_TRAINER]);

        $manager->persist($userTrainer);

        $trainer->setName('John Trainer Doe');
        $trainer->setPhone('+37065867812');
        $trainer->addTag($tagObjects[0]);
        $trainer->setPersonalStatement('Hello! My name is John and I\'m ready to help you in reaching 
        your fitness goals. My clients love working with me as much as I love sports. I always make sure I provide the 
        best workout advice for my customers. It will be fun, I promise!');
        $trainer->setLocation('Vilnius');
        $trainer->setImageName($imageNames[3]);
        $trainer->setUser($userTrainer);

        $manager->persist($trainer);

        $customer = new Customer();
        $userCustomer = new User();
        $userCustomer->setEmail('customer123@train.me');
        $userCustomer->setPassword($this->passwordEncoder->encodePassword($userCustomer, 'customer123'));
        $userCustomer->setRoles([User::ROLE_CUSTOMER]);
        $manager->persist($userCustomer);

        $customer->setName('John Customer Doe');
        $customer->setPhone('+37064565289');
        $customer->setUser($userCustomer);
        $manager->persist($customer);

        $rating = new Rating();
        $rating->setStars(5);
        $rating->setTrainer($trainer);
        $rating->setCustomer($dummyCustomer);
        $manager->persist($rating);

        $manager->flush();
    }

    private function getImageNames()
    {
        $fileNames = [];
        $finder = new Finder();
        $imagesDir = $this->kernel->getProjectDir() . '/public/images/profile/';

        $finder->files()->in($imagesDir);
        foreach ($finder as $file) {
            $fileNames[] = $file->getFilename();
        }
        return $fileNames;
    }
}
