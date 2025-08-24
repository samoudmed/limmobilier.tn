<?php
// src/App/Entity/gouvernorat.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="gouvernorat")
 */
class Gouvernorat {

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
     * @ORM\OneToMany(targetEntity="App\Entity\Delegation", mappedBy="gouvernorat")
     */
    private $delegations;

    /**
     * @ORM\ManyToOne(targetEntity="Pays")
     * @ORM\JoinColumn(name="pays_id", referencedColumnName="id")
     */
    private $pays;

    public function __construct()
    {
        $this->delegations = new ArrayCollection();
    }

    public function __toString(): string {
        return $this->getLabel();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getLabel(): ?string {
        return $this->label;
    }

    public function setLabel(string $label): self {
        $this->label = $label;

        return $this;
    }

    public function getPays(): ?Pays {
        return $this->pays;
    }

    public function setPays(?Pays $pays): self {
        $this->pays = $pays;

        return $this;
    }

    public function getSlug(): ?string {
        return $this->slug;
    }

    public function setSlug(string $slug): self {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Delegation[]
     */
    public function getDelegations(): Collection {
        return $this->delegations;
    }

    public function addDelegation(Delegation $delegation): self {
        if (!$this->delegations->contains($delegation)) {
            $this->delegations[] = $delegation;
            $delegation->setGouvernorat($this);
        }

        return $this;
    }

    public function removeDelegation(Delegation $delegation): self {
        if ($this->delegations->removeElement($delegation)) {
            // set the owning side to null (unless already changed)
            if ($delegation->getGouvernorat() === $this) {
                $delegation->setGouvernorat(null);
            }
        }

        return $this;
    }

}