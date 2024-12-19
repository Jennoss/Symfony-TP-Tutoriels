<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Tutoriel::class, mappedBy: 'matiere', orphanRemoval: true)]
    private Collection $tutoriels;

    public function __construct()
    {
        $this->tutoriels = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getTutoriels(): Collection
    {
        return $this->tutoriels;
    }

    public function addTutoriel(Tutoriel $tutoriel): self
    {
        if (!$this->tutoriels->contains($tutoriel)) {
            $this->tutoriels->add($tutoriel);
            $tutoriel->setMatiere($this);
        }
        return $this;
    }

    public function removeTutoriel(Tutoriel $tutoriel): self
    {
        if ($this->tutoriels->removeElement($tutoriel)) {
            if ($tutoriel->getMatiere() === $this) {
                $tutoriel->setMatiere(null);
            }
        }
        return $this;
    }
}
