<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 * @UniqueEntity(fields={"libelle"})
 */
class Etat
{
    const CREE = 'Créee';
    const ANNULEE = 'Annulée';
    const ENCOURS = 'Activité en cours';
    const CLOTUREE = 'Clôturée';
    const OUVERTE = 'Ouverte';
    const PASSEE = 'Passée';
    const HISTORISEE = 'Historisée';


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="libelle", type="string", length=50, unique=true)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="etat")
     */
    private $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSortie(Sortie $sortie): self
    {
        if (!$this->sorties->contains($sortie)) {
            $this->sorties[] = $sortie;
            $sortie->setEtat($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sortie): self
    {
        $this->sorties->removeElement($sortie);
        /*
        if ($this->sorties->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getEtat() === $this) {
                $sortie->setEtat(null);
            }
        }
        */

        return $this;
    }
}
