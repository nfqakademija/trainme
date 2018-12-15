<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
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
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ScheduledWorkout", mappedBy="customer")
     */
    private $scheduledWorkouts;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="customer")
     * @ORM\JoinColumn(nullable=false)
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

    public function __construct()
    {
        $this->scheduledWorkouts = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

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

    public function addScheduledWorkout(scheduledWorkout $scheduledWorkout): self
    {
        if (!$this->scheduledWorkouts->contains($scheduledWorkout)) {
            $this->scheduledWorkouts[] = $scheduledWorkout;
            $scheduledWorkout->setCustomer($this);
        }

        return $this;
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

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

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setCustomer($this);
        }

        return $this;
    }

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
