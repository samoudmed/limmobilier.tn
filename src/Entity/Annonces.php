<?php

// src/App/Entity/annonces.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnoncesRepository")
 * @ORM\Table(name="annonces")
 */
class Annonces {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $label;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $surface;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $localisationMap;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     */
    private $offre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prix;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $instalment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $view;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $realView;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $orientation;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $climatiseur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anneeConstruction;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pieces;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $piscine;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $parking;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $chauffage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacite;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $internet;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $meuble;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $salleBain;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $securite;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ascenseur;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cheminee;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cuisineEquipe;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $jacuzzi;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $jardin;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $electricite;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $gaz;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $eau;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $assainissement;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $permisConstruction;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $vue;
    
     /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $disponibilite;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="Villes", cascade={"persist"})
     * @ORM\JoinColumn(name="ville_id", referencedColumnName="id")
     */
    private $ville;
    
    /**
     * @ORM\ManyToOne(targetEntity="Gouvernorat", cascade={"persist"})
     * @ORM\JoinColumn(name="gouvernorat_id", referencedColumnName="id")
     */
    private $gouvernorat;
    
    /**
     * @ORM\ManyToOne(targetEntity="Delegation", cascade={"persist"})
     * @ORM\JoinColumn(name="delegation_id", referencedColumnName="id")
     */
    private $delegation;

    /**
     * @ORM\ManyToOne(targetEntity="Pays", cascade={"persist"})
     * @ORM\JoinColumn(name="pays_id", referencedColumnName="id")
     */
    private $pays;

    /**
     * @ORM\ManyToOne(targetEntity="Kind", cascade={"persist"})
     * @ORM\JoinColumn(name="kind_id", referencedColumnName="id")
     */
    private $kind;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="annonces")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $published;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deleted;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $archived;

    /**
     * @ORM\OneToMany(targetEntity=Photos::class, mappedBy="annonce", orphanRemoval=true)
     */
    private $photos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $expired_at;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;
    
    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getLabel();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(?int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getLocalisationMap(): ?string
    {
        return $this->localisationMap;
    }

    public function setLocalisationMap(?string $localisationMap): self
    {
        $this->localisationMap = $localisationMap;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOffre(): ?string
    {
        return $this->offre;
    }

    public function setOffre(string $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getView(): ?int
    {
        return $this->view;
    }

    public function setView(?int $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function getOrientation(): ?string
    {
        return $this->orientation;
    }

    public function setOrientation(?string $orientation): self
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function isClimatiseur(): ?bool
    {
        return $this->climatiseur;
    }

    public function setClimatiseur(?bool $climatiseur): self
    {
        $this->climatiseur = $climatiseur;

        return $this;
    }

    public function getAnneeConstruction(): ?int
    {
        return $this->anneeConstruction;
    }

    public function setAnneeConstruction(?int $anneeConstruction): self
    {
        $this->anneeConstruction = $anneeConstruction;

        return $this;
    }

    public function getPieces(): ?int
    {
        return $this->pieces;
    }

    public function setPieces(?int $pieces): self
    {
        $this->pieces = $pieces;

        return $this;
    }

    public function isPiscine(): ?bool
    {
        return $this->piscine;
    }

    public function setPiscine(?bool $piscine): self
    {
        $this->piscine = $piscine;

        return $this;
    }

    public function isParking(): ?bool
    {
        return $this->parking;
    }

    public function setParking(?bool $parking): self
    {
        $this->parking = $parking;

        return $this;
    }

    public function isChauffage(): ?bool
    {
        return $this->chauffage;
    }

    public function setChauffage(?bool $chauffage): self
    {
        $this->chauffage = $chauffage;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(?int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function isInternet(): ?bool
    {
        return $this->internet;
    }

    public function setInternet(?bool $internet): self
    {
        $this->internet = $internet;

        return $this;
    }

    public function isMeuble(): ?bool
    {
        return $this->meuble;
    }

    public function setMeuble(?bool $meuble): self
    {
        $this->meuble = $meuble;

        return $this;
    }

    public function isSalleBain(): ?bool
    {
        return $this->salleBain;
    }

    public function setSalleBain(?bool $salleBain): self
    {
        $this->salleBain = $salleBain;

        return $this;
    }

    public function isSecurite(): ?bool
    {
        return $this->securite;
    }

    public function setSecurite(?bool $securite): self
    {
        $this->securite = $securite;

        return $this;
    }

    public function isAscenseur(): ?bool
    {
        return $this->ascenseur;
    }

    public function setAscenseur(?bool $ascenseur): self
    {
        $this->ascenseur = $ascenseur;

        return $this;
    }

    public function isCheminee(): ?bool
    {
        return $this->cheminee;
    }

    public function setCheminee(?bool $cheminee): self
    {
        $this->cheminee = $cheminee;

        return $this;
    }

    public function isCuisineEquipe(): ?bool
    {
        return $this->cuisineEquipe;
    }

    public function setCuisineEquipe(?bool $cuisineEquipe): self
    {
        $this->cuisineEquipe = $cuisineEquipe;

        return $this;
    }

    public function isJacuzzi(): ?bool
    {
        return $this->jacuzzi;
    }

    public function setJacuzzi(?bool $jacuzzi): self
    {
        $this->jacuzzi = $jacuzzi;

        return $this;
    }

    public function isJardin(): ?bool
    {
        return $this->jardin;
    }

    public function setJardin(?bool $jardin): self
    {
        $this->jardin = $jardin;

        return $this;
    }

    public function isElectricite(): ?bool
    {
        return $this->electricite;
    }

    public function setElectricite(?bool $electricite): self
    {
        $this->electricite = $electricite;

        return $this;
    }

    public function isGaz(): ?bool
    {
        return $this->gaz;
    }

    public function setGaz(?bool $gaz): self
    {
        $this->gaz = $gaz;

        return $this;
    }

    public function isTelephone(): ?bool
    {
        return $this->telephone;
    }

    public function setTelephone(?bool $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isEau(): ?bool
    {
        return $this->eau;
    }

    public function setEau(?bool $eau): self
    {
        $this->eau = $eau;

        return $this;
    }

    public function isAssainissement(): ?bool
    {
        return $this->assainissement;
    }

    public function setAssainissement(?bool $assainissement): self
    {
        $this->assainissement = $assainissement;

        return $this;
    }

    public function isPermisConstruction(): ?bool
    {
        return $this->permisConstruction;
    }

    public function setPermisConstruction(?bool $permisConstruction): self
    {
        $this->permisConstruction = $permisConstruction;

        return $this;
    }

    public function isVue(): ?bool
    {
        return $this->vue;
    }

    public function setVue(?bool $vue): self
    {
        $this->vue = $vue;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expired_at;
    }

    public function setExpiredAt(?\DateTimeInterface $expired_at): self
    {
        $this->expired_at = $expired_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDisponibilite(): ?\DateTimeInterface
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(?\DateTimeInterface $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getEtage(): ?string
    {
        return $this->etage;
    }

    public function setEtage(?string $etage): self
    {
        $this->etage = $etage;

        return $this;
    }

    public function getVille(): ?Villes
    {
        return $this->ville;
    }

    public function setVille(?Villes $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getGouvernorat(): ?Gouvernorat
    {
        return $this->gouvernorat;
    }

    public function setGouvernorat(?Gouvernorat $gouvernorat): self
    {
        $this->gouvernorat = $gouvernorat;

        return $this;
    }

    public function getDelegation(): ?Delegation
    {
        return $this->delegation;
    }

    public function setDelegation(?Delegation $delegation): self
    {
        $this->delegation = $delegation;

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Photos>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photos $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAnnonce($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getAnnonce() === $this) {
                $photo->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getInstalment(): ?string
    {
        return $this->instalment;
    }

    public function setInstalment(?string $instalment): self
    {
        $this->instalment = $instalment;

        return $this;
    }

    public function getRealView(): ?int
    {
        return $this->realView;
    }

    public function setRealView(?int $realView): self
    {
        $this->realView = $realView;

        return $this;
    }

    public function getKind(): ?Kind
    {
        return $this->kind;
    }

    public function setKind(?Kind $kind): static
    {
        $this->kind = $kind;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(?bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }


}