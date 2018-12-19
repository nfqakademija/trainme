<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RatingRepository")
 */
class Rating
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = 1,
     *      max = 5,
     *      minMessage = "Minimum rating is 1 star.",
     *      maxMessage = "Maximum rating is 5 stars."
     * )
     */
    private $stars;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="ratings")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trainer", inversedBy="ratings")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $trainer;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getStars(): ?int
    {
        return $this->stars;
    }

    /**
     * @param int $stars
     * @return Rating
     */
    public function setStars(int $stars): self
    {
        $this->stars = $stars;

        return $this;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     * @return Rating
     */
    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Trainer|null
     */
    public function getTrainer(): ?Trainer
    {
        return $this->trainer;
    }

    /**
     * @param Trainer|null $trainer
     * @return Rating
     */
    public function setTrainer(?Trainer $trainer): self
    {
        $this->trainer = $trainer;

        return $this;
    }
}
