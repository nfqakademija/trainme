<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainerRepository")
 */
class Trainer
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
     * @ORM\Column(type="text")
     */
    private $personalStatement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AvailabilitySlot", mappedBy="trainer")
     */
    private $availabilitySlots;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ScheduledWorkout", mappedBy="trainer")
     */
    private $scheduledWorkouts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="trainer")
     */
    private $tags;

    public function __construct()
    {
        $this->availabilitySlots = new ArrayCollection();
        $this->scheduledWorkouts = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getPersonalStatement(): ?string
    {
        return $this->personalStatement;
    }

    public function setPersonalStatement(string $personalStatement): self
    {
        $this->personalStatement = $personalStatement;

        return $this;
    }

    /**
     * @return Collection|AvailabilitySlot[]
     */
    public function getAvailabilitySlots(): Collection
    {
        return $this->availabilitySlots;
    }

    public function addAvailabilitySlot(AvailabilitySlot $availabilitySlot): self
    {
        if (!$this->availabilitySlots->contains($availabilitySlot)) {
            $this->availabilitySlots[] = $availabilitySlot;
            $availabilitySlot->setTrainer($this);
        }

        return $this;
    }

    public function removeAvailabilitySlot(AvailabilitySlot $availabilitySlot): self
    {
        if ($this->availabilitySlots->contains($availabilitySlot)) {
            $this->availabilitySlots->removeElement($availabilitySlot);
            // set the owning side to null (unless already changed)
            if ($availabilitySlot->getTrainer() === $this) {
                $availabilitySlot->setTrainer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ScheduledWorkout[]
     */
    public function getScheduledWorkouts(): Collection
    {
        return $this->scheduledWorkouts;
    }

    public function addScheduledWorkout(ScheduledWorkout $scheduledWorkout): self
    {
        if (!$this->scheduledWorkouts->contains($scheduledWorkout)) {
            $this->scheduledWorkouts[] = $scheduledWorkout;
            $scheduledWorkout->setTrainer($this);
        }

        return $this;
    }

    public function removeScheduledWorkout(ScheduledWorkout $scheduledWorkout): self
    {
        if ($this->scheduledWorkouts->contains($scheduledWorkout)) {
            $this->scheduledWorkouts->removeElement($scheduledWorkout);
            // set the owning side to null (unless already changed)
            if ($scheduledWorkout->getTrainer() === $this) {
                $scheduledWorkout->setTrainer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addTrainer($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeTrainer($this);
        }

        return $this;
    }
}
