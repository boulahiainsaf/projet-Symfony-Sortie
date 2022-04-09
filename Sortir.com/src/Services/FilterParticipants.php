<?php


namespace App\Services;


class FilterParticipants
{
    private ?string $nomParticipant = null;

    /**
     * @return string|null
     */
    public function getNomParticipant(): ?string
    {
        return $this->nomParticipant;
    }

    /**
     * @param string|null $nomParticipant
     */
    public function setNomParticipant(?string $nomParticipant): void
    {
        $this->nomParticipant = $nomParticipant;
    }

}