<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $intitule_session;

    /**
     * @ORM\Column(type="date")
     */
    private $date_debut;

    /**
     * @ORM\Column(type="date")
     */
    private $date_fin;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_place;

    /**
     * @ORM\ManyToMany(targetEntity=Stagiaire::class, mappedBy="sessions")
     */
    private $stagiaires;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="sessions")
     */
    private $formation;

    /**
     * @ORM\OneToMany(targetEntity=SessionProgramme::class, mappedBy="session", cascade={"persist"})
     */
    private $sessionProgrammes;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="sessions")
     */
    private $formateur;

    public function __construct()
    {
        $this->stagiaires = new ArrayCollection();
        $this->sessionProgrammes = new ArrayCollection();
    }

    /**
     * Get the value of id.
     */
    public function getId()
    {
        return $this->id;
    }

    public function getIntituleSession(): ?string
    {
        return $this->intitule_session;
    }

    public function setIntituleSession(string $intitule_session): self
    {
        $this->intitule_session = $intitule_session;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nb_place;
    }

    public function setNbPlace(int $nb_place): self
    {
        $this->nb_place = $nb_place;

        return $this;
    }

    public function getNbPlacesRestantes()
    {
        return $this->nb_place - count($this->stagiaires);
    }

    /**
     * @return Collection<int, Stagiaire>
     */
    public function getStagiaires(): Collection
    {
        return $this->stagiaires;
    }

    public function addStagiaire(Stagiaire $stagiaire): self
    {
        if (!$this->stagiaires->contains($stagiaire)) {
            $this->stagiaires[] = $stagiaire;
            $stagiaire->addSession($this);
        }

        return $this;
    }

    public function removeStagiaire(Stagiaire $stagiaire): self
    {
        if ($this->stagiaires->removeElement($stagiaire)) {
            $stagiaire->removeSession($this);
        }

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

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
            $sessionProgramme->setSession($this);
        }

        return $this;
    }

    public function removeSessionProgramme(SessionProgramme $sessionProgramme): self
    {
        if ($this->sessionProgrammes->removeElement($sessionProgramme)) {
            // set the owning side to null (unless already changed)
            if ($sessionProgramme->getSession() === $this) {
                $sessionProgramme->setSession(null);
            }
        }

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }
}
