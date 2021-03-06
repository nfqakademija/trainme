<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Tag implements \JsonSerializable
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
     * @var Collection/Trainer[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Trainer", inversedBy="tags")
     */
    private $trainers;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->trainers = new ArrayCollection();
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
     * @return Tag
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Trainer[]
     */
    public function getTrainers(): Collection
    {
        return $this->trainers;
    }

    /**
     * @param Trainer $trainer
     * @return Tag
     */
    public function addTrainer(Trainer $trainer): self
    {
        if (!$this->trainers->contains($trainer)) {
            $this->trainers[] = $trainer;
        }

        return $this;
    }

    /**
     * @param Trainer $trainer
     * @return Tag
     */
    public function removeTrainer(Trainer $trainer): self
    {
        if ($this->trainers->contains($trainer)) {
            $this->trainers->removeElement($trainer);
        }

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName()
        ];
    }
}
