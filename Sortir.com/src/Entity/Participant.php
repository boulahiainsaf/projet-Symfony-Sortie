<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity(fields={"email"})
 * @UniqueEntity(fields={"pseudo"})
 */
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=180)
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     *
     *
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $motPasse;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50 ,unique=true)
     */
    private $pseudo;

    /**
     * @Assert\Length(max=10)
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $telephone;

    /**
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur")
     */
    private $sorties;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="participants")
     */
    private $sortiesInscription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageFile;


    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->sortiesInscription = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // TODO si admin  =  true -> return ROLE_ADMIN dans un tableau
        // $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';
        return $this->administrateur?['ROLE_ADMIN']:['ROLE_USER'];
        //return array_unique($roles);
    }
/*
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
*/
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->motPasse;
    }

    public function getMotPasse(): string
    {
        return $this->motPasse;
    }

    public function setMotPasse(string $motPasse): self
    {
        $this->motPasse = $motPasse;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        $this->sorties->removeElement($sorty);
        /*
       if ($this->sorties->removeElement($sorty)) {
           $sorty->removeParticipant($this);
           // set the owning side to null (unless already changed)
           if ($sorty->getParticipant() === $this) {
               $sorty->setParticipant(null);
           }

        }
*/
        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesInscription(): Collection
    {
        return $this->sortiesInscription;
    }

    public function addSortieInscription(Sortie $sortieInscription): self
    {
        if (!$this->sortiesInscription->contains($sortieInscription)) {
            $this->sortiesInscription[] = $sortieInscription;
            $sortieInscription->addParticipant($this);
        }

        return $this;
    }

    public function removeSortieInscription(Sortie $sortieInscription): self
    {
        if($this->sortiesInscription->removeElement($sortieInscription)){
            $sortieInscription->removeParticipant($this);
        }
        return $this;
    }

    public function getImageFile(): ?string
    {
        return $this->imageFile;
    }

    public function setImageFile(?string $imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }


}
