<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Trainer;
use App\Entity\User;

class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @return User
     * @throws \Exception
     */
    public function getUser()
    {
        $user = parent::getUser();

        if (!$user instanceof User) {
            throw new \Exception('User expected');
        }

        return $user;
    }

    /**
     * @throws \Exception
     * @return Trainer
     */
    public function getTrainer()
    {
        $user = $this->getUser();

        $trainer = $user->getTrainer();

        if (!$trainer) {
            throw new \Exception('Trainer data is not available');
        }

        return $trainer;
    }

    /**
     * @throws \Exception
     * @return Customer
     */
    public function getCustomer()
    {
        $user = $this->getUser();

        $customer = $user->getCustomer();

        if (!$customer) {
            throw new \Exception('Customer data is not available');
        }

        return $customer;
    }
}
