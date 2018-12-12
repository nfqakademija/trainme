<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainerRepository")
 */
class Trainer implements \JsonSerializable
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $personalStatement;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $location;

    /**
     * @var Collection|AvailabilitySlot[]
     * @ORM\OneToMany(targetEntity="App\Entity\AvailabilitySlot", mappedBy="trainer")
     */
    private $availabilitySlots;

    /**
     * @var Collection|ScheduledWorkout[]
     * @ORM\OneToMany(targetEntity="App\Entity\ScheduledWorkout", mappedBy="trainer")
     */
    private $scheduledWorkouts;

    /**
     * @var Collection|Tag[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="trainer")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_url;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"}, inversedBy="trainer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="trainer")
     */
    private $ratings;

    /**
     * Trainer constructor.
     */
    public function __construct()
    {
        $this->availabilitySlots = new ArrayCollection();
        $this->scheduledWorkouts = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Trainer
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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
     * @return Trainer
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPersonalStatement(): ?string
    {
        return $this->personalStatement;
    }

    /**
     * @param string $personalStatement
     * @return Trainer
     */
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

    /**
     * @param AvailabilitySlot $availabilitySlot
     * @return Trainer
     */
    public function addAvailabilitySlot(AvailabilitySlot $availabilitySlot): self
    {
        if (!$this->availabilitySlots->contains($availabilitySlot)) {
            $this->availabilitySlots[] = $availabilitySlot;
            $availabilitySlot->setTrainer($this);
        }

        return $this;
    }

    /**
     * @param AvailabilitySlot $availabilitySlot
     * @return Trainer
     */
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

    /**
     * @param ScheduledWorkout $scheduledWorkout
     * @return Trainer
     */
    public function addScheduledWorkout(ScheduledWorkout $scheduledWorkout): self
    {
        if (!$this->scheduledWorkouts->contains($scheduledWorkout)) {
            $this->scheduledWorkouts[] = $scheduledWorkout;
            $scheduledWorkout->setTrainer($this);
        }

        return $this;
    }

    /**
     * @param ScheduledWorkout $scheduledWorkout
     * @return Trainer
     */
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

    /**
     * @param Tag $tag
     * @return Trainer
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addTrainer($this);
        }

        return $this;
    }

    /**
     * @param Tag $tag
     * @return Trainer
     */
    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeTrainer($this);
        }

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): self
    {
        $this->image_url = $image_url;

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
     * @return string
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'personalStatement' => $this->personalStatement,
            'imageUrl' => $this->image_url,
            'location' => $this->location
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
            $rating->setTrainer($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getTrainer() === $this) {
                $rating->setTrainer(null);
            }
        }

        return $this;
    }
}
