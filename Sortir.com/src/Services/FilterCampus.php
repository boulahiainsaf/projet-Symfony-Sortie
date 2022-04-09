<?php

namespace App\Services;

class FilterCampus
{

    private ?string $nomCampusContient = null;

    /**
     * @return string|null
     */
    public function getNomCampusContient(): ?string
    {
        return $this->nomCampusContient;
    }

    /**
     * @param string|null $nomCampusContient
     */
    public function setNomCampusContient(?string $nomCampusContient): void
    {
        $this->nomCampusContient = $nomCampusContient;
    }

}