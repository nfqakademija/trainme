<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Customer implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ScheduledWorkout", mappedBy="customer")
     */
    private $scheduledWorkouts;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="customer", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="customer")
     */
    private $ratings;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $hasEvaluatedTrainerOnLogin = false;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        $this->scheduledWorkouts = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Customer
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection|scheduledWorkout[]
     */
    public function getScheduledWorkouts(): Collection
    {
        return $this->scheduledWorkouts;
    }

    /**
     * @param scheduledWorkout $scheduledWorkout
     * @return Customer
     */
    public function addScheduledWorkout(scheduledWorkout $scheduledWorkout): self
    {
        if (!$this->scheduledWorkouts->contains($scheduledWorkout)) {
            $this->scheduledWorkouts[] = $scheduledWorkout;
            $scheduledWorkout->setCustomer($this);
        }

        return $this;
    }

    /**
     * @param scheduledWorkout $scheduledWorkout
     * @return Customer
     */
    public function removeScheduledWorkout(scheduledWorkout $scheduledWorkout): self
    {
        if ($this->scheduledWorkouts->contains($scheduledWorkout)) {
            $this->scheduledWorkouts->removeElement($scheduledWorkout);
            // set the owning side to null (unless already changed)
            if ($scheduledWorkout->getCustomer() === $this) {
                $scheduledWorkout->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Customer
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
        ];
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    /**
     * @param Rating $rating
     * @return Customer
     */
    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setCustomer($this);
        }

        return $this;
    }

    /**
     * @param Rating $rating
     * @return Customer
     */
    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getCustomer() === $this) {
                $rating->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function getHasEvaluatedTrainerOnLogin(): bool
    {
        return $this->hasEvaluatedTrainerOnLogin;
    }

    /**
     * @param bool $hasEvaluatedTrainerOnLogin
     */
    public function setHasEvaluatedTrainerOnLogin(bool $hasEvaluatedTrainerOnLogin): void
    {
        $this->hasEvaluatedTrainerOnLogin = $hasEvaluatedTrainerOnLogin;
    }
}
