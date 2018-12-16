<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainerRepository")
 * @Vich\Uploadable
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="trainers")
     */
    private $tags;

    /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/jpeg"},
     *     mimeTypesMessage = "Please upload a valid Image"
     * )
     * @Vich\UploadableField(mapping="profile_photo", fileNameProperty="imageName")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageName;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"}, inversedBy="trainer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Collection|Rating
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="trainer")
     */
    private $ratings;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt = null;

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

    /**
     * @return null|string
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @param null|string $imageName
     * @return Trainer
     */
    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param null|File $image
     * @throws \Exception
     */
    public function setImageFile(?File $image = null): void
    {
        if (null !== $image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
        $this->imageFile = $image;
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
     * @return Trainer
     */
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

    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    /**
     * @param Rating $rating
     * @return Trainer
     */
    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setTrainer($this);
        }

        return $this;
    }

    /**
     * @param Rating $rating
     * @return Trainer
     */
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

    /**
     * @return float|int|null
     */
    public function getAverageRating()
    {
        $ratings = $this->getRatings();
        $ratingsCount = $ratings->count();

        if ($ratingsCount == 0) {
            return null;
        }

        $sum = 0;
        foreach ($this->getRatings() as $rating) {
            $sum += $rating->getStars();
        }

        return $sum / $ratingsCount;
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
            'personalStatement' => $this->personalStatement,
            'imageName' => $this->imageName,
            'location' => $this->location,
            'tags' => $this->tags ?? $this->tags->toArray()
        ];
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return \array_keys($this->jsonSerialize());
    }
}
