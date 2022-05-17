<?php

namespace App\Entity;

use App\Repository\SessionProgrammeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SessionProgrammeRepository::class)
 */
class SessionProgramme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_jours_cours;

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="sessionProgrammes")
     */
    private $session;

    /**
     * @ORM\ManyToOne(targetEntity=Cours::class, inversedBy="sessionProgrammes")
     */
    private $cours;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbJoursCours(): ?int
    {
        return $this->nb_jours_cours;
    }

    public function setNbJoursCours(int $nb_jours_cours): self
    {
        $this->nb_jours_cours = $nb_jours_cours;

        return $this;
    }

    public function getSession(): ?session
    {
        return $this->session;
    }

    public function setSession(?session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;

        return $this;
    }
}
