<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CoursRepository::class)
 */
class Cours
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom_cours;

    /**
     * @ORM\OneToMany(targetEntity=SessionProgramme::class, mappedBy="cours")
     */
    private $sessionProgrammes;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="cours")
     */
    private $categorie;

    public function __construct()
    {
        $this->sessionProgrammes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCours(): ?string
    {
        return $this->nom_cours;
    }

    public function setNomCours(string $nom_cours): self
    {
        $this->nom_cours = $nom_cours;

        return $this;
    }

    /**
     * @return Collection<int, SessionProgramme>
     */
    public function getSessionProgrammes(): Collection
    {
        return $this->sessionProgrammes;
    }

    public function addSessionProgramme(SessionProgramme $sessionProgramme): self
    {
        if (!$this->sessionProgrammes->contains($sessionProgramme)) {
            $this->sessionProgrammes[] = $sessionProgramme;
            $sessionProgramme->setCours($this);
        }

        return $this;
    }

    public function removeSessionProgramme(SessionProgramme $sessionProgramme): self
    {
        if ($this->sessionProgrammes->removeElement($sessionProgramme)) {
            // set the owning side to null (unless already changed)
            if ($sessionProgramme->getCours() === $this) {
                $sessionProgramme->setCours(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function __toString()
    {
        return $this->nom_cours;
    }
}
