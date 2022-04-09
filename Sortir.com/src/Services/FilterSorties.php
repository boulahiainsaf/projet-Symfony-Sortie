<?php


namespace App\Services;


use App\Entity\Campus;
use Symfony\Component\Validator\Constraints as Assert;

class FilterSorties
{
    private ?Campus $campus = null;
    private ?string $nomSortie = null;
    /**
     * @Assert\LessThanOrEqual(propertyPath="dateFin")
     */
    private ?\DateTime $dateDebut = null;
    /**
     * @Assert\GreaterThanOrEqual(propertyPath="dateDebut")
     */
    private ?\DateTime $dateFin = null;
    private bool $sortiesOrganises = false;
    private bool $sortiesInscrites = false;
    private bool $sortiesPasInscrites = false;
    private bool $sortiesPassees = false;

    public function __construct()
    {
    }

    /**
     * @return Campus|null
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus|null $campus
     */
    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return string|null
     */
    public function getNomSortie(): ?string
    {
        return $this->nomSortie;
    }

    /**
     * @param string|null $nomSortie
     */
    public function setNomSortie(?string $nomSortie): void
    {
        $this->nomSortie = $nomSortie;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTime|null $dateDebut
     */
    public function setDateDebut(?\DateTime $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    /**
     * @param \DateTime|null $dateFin
     */
    public function setDateFin(?\DateTime $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return bool
     */
    public function isSortiesOrganises(): bool
    {
        return $this->sortiesOrganises;
    }

    /**
     * @param bool $sortiesOrganises
     */
    public function setSortiesOrganises(bool $sortiesOrganises): void
    {
        $this->sortiesOrganises = $sortiesOrganises;
    }

    /**
     * @return bool
     */
    public function isSortiesInscrites(): bool
    {
        return $this->sortiesInscrites;
    }

    /**
     * @param bool $sortiesInscrites
     */
    public function setSortiesInscrites(bool $sortiesInscrites): void
    {
        $this->sortiesInscrites = $sortiesInscrites;
    }

    /**
     * @return bool
     */
    public function isSortiesPasInscrites(): bool
    {
        return $this->sortiesPasInscrites;
    }

    /**
     * @param bool $sortiesPasInscrites
     */
    public function setSortiesPasInscrites(bool $sortiesPasInscrites): void
    {
        $this->sortiesPasInscrites = $sortiesPasInscrites;
    }

    /**
     * @return bool
     */
    public function isSortiesPassees(): bool
    {
        return $this->sortiesPassees;
    }

    /**
     * @param bool $sortiesPassees
     */
    public function setSortiesPassees(bool $sortiesPassees): void
    {
        $this->sortiesPassees = $sortiesPassees;
    }

   }