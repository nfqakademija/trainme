<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScheduledWorkoutRepository")
 */
class ScheduledWorkout implements \JsonSerializable
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $startsAt;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $endsAt;

    /**
     * @var Trainer
     * @ORM\ManyToOne(targetEntity="App\Entity\Trainer", inversedBy="scheduledWorkouts")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $trainer;

    /**
     * @var Customer
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="scheduledWorkouts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartsAt(): ?\DateTimeInterface
    {
        return $this->startsAt;
    }

    /**
     * @param \DateTimeInterface $startsAt
     * @return ScheduledWorkout
     */
    public function setStartsAt(\DateTimeInterface $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndsAt(): ?\DateTimeInterface
    {
        return $this->endsAt;
    }

    /**
     * @param \DateTimeInterface $endsAt
     * @return ScheduledWorkout
     */
    public function setEndsAt(\DateTimeInterface $endsAt): self
    {
        $this->endsAt = $endsAt;

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
     * @return ScheduledWorkout
     */
    public function setTrainer(?Trainer $trainer): self
    {
        $this->trainer = $trainer;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'starts_at' => $this->startsAt->format('Y-m-d H:i:s'),
            'ends_at' => $this->endsAt->format('Y-m-d H:i:s'),
            'trainer_id' => $this->trainer->getId(),
            'customer_id' => $this->customer->getId()
        ];
    }
}
