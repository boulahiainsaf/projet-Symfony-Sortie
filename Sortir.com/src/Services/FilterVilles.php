<?php

namespace App\Services;

class FilterVilles
{
    private ?string $nomVillesContient = null;

    /**
     * @return string|null
     */
    public function getNomVillesContient(): ?string
    {
        return $this->nomVillesContient;
    }

    /**
     * @param string|null $nomVillesContient
     */
    public function setNomVillesContient(?string $nomVillesContient): void
    {
        $this->nomVillesContient = $nomVillesContient;
    }
}