<?php

// src/App/Entity/NewsletterGeo.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsletterGeoRepository")
 * @ORM\Table(name="newsletter_geo")
 */
class NewsletterGeo {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $offre;
    
    /**
     * @ORM\ManyToOne(targetEntity="Kind", cascade={"persist"})
     * @ORM\JoinColumn(name="kind_id", referencedColumnName="id", nullable=true)
     */
    private $kind;

    /**
     * @ORM\ManyToOne(targetEntity="Villes", cascade={"persist"})
     * @ORM\JoinColumn(name="ville_id", referencedColumnName="id", nullable=true)
     */
    private $ville;
    
    /**
     * @ORM\ManyToOne(targetEntity="Gouvernorat", cascade={"persist"})
     * @ORM\JoinColumn(name="gouvernorat_id", referencedColumnName="id", nullable=true)
     */
    private $gouvernorat;
    
    /**
     * @ORM\ManyToOne(targetEntity="Delegation", cascade={"persist"})
     * @ORM\JoinColumn(name="delegation_id", referencedColumnName="id", nullable=true)
     */
    private $delegation;
    
    /**
     * @ORM\ManyToOne(targetEntity="Newsletter", cascade={"persist"}, inversedBy="newsletterGeo")
     * @ORM\JoinColumn(name="newsletter_id", referencedColumnName="id")
     */
    private $newsletter;

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

    public function getNewsletter(): ?Newsletter
    {
        return $this->newsletter;
    }

    public function setNewsletter(?Newsletter $newsletter): self
    {
        $this->newsletter = $newsletter;

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
