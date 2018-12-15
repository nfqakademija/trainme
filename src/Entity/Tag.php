<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
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
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @var Trainer
     * @ORM\ManyToMany(targetEntity="App\Entity\Trainer", inversedBy="tags")
     */
    private $trainer;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->trainer = new ArrayCollection();
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
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Tag
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Trainer[]
     */
    public function getTrainer(): Collection
    {
        return $this->trainer;
    }

    /**
     * @param Trainer $trainer
     * @return Tag
     */
    public function addTrainer(Trainer $trainer): self
    {
        if (!$this->trainer->contains($trainer)) {
            $this->trainer[] = $trainer;
        }

        return $this;
    }

    /**
     * @param Trainer $trainer
     * @return Tag
     */
    public function removeTrainer(Trainer $trainer): self
    {
        if ($this->trainer->contains($trainer)) {
            $this->trainer->removeElement($trainer);
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'trainer_id' => $this->trainer->getId()
        ];
    }
}
