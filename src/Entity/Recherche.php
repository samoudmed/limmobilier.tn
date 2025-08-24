<?php
// src/App/Entity/Recherche.php
namespace App\Entity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RechercheRepository")
 * @ORM\Table(name="recherche")
 */

class Recherche
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Kind", cascade={"persist"})
     * @ORM\JoinColumn(name="kind_id", referencedColumnName="id")
     */
    private $kind;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $offre;

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
     * @ORM\Column(type="string", nullable=true)
     */
    private $pieces;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $surfaceMin;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $surfaceMax;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prixMin;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prixMax;
    
    /**
     * @ORM\Column(type="date")
     */
    private $date;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOffre(): ?string
    {
        return $this->offre;
    }

    public function setOffre(?string $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getPieces(): ?string
    {
        return $this->pieces;
    }

    public function setPieces(?string $pieces): self
    {
        $this->pieces = $pieces;

        return $this;
    }

    public function getSurfaceMin(): ?int
    {
        return $this->surfaceMin;
    }

    public function setSurfaceMin(?int $surfaceMin): self
    {
        $this->surfaceMin = $surfaceMin;

        return $this;
    }

    public function getSurfaceMax(): ?int
    {
        return $this->surfaceMax;
    }

    public function setSurfaceMax(?int $surfaceMax): self
    {
        $this->surfaceMax = $surfaceMax;

        return $this;
    }

    public function getPrixMin(): ?int
    {
        return $this->prixMin;
    }

    public function setPrixMin(?int $prixMin): self
    {
        $this->prixMin = $prixMin;

        return $this;
    }

    public function getPrixMax(): ?int
    {
        return $this->prixMax;
    }

    public function setPrixMax(?int $prixMax): self
    {
        $this->prixMax = $prixMax;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getKind(): ?Kind
    {
        return $this->kind;
    }

    public function setKind(?Kind $kind): static
    {
        $this->kind = $kind;

        return $this;
    }

    
}
