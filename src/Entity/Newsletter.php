<?php

// src/App/Entity/newsletter.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsletterRepository")
 * @ORM\Table(name="newsletter")
 */
class Newsletter {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $prenom;
    
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $unsubscribed;
    
    /**
     * @ORM\OneToMany(targetEntity="NewsletterGeo", cascade={"persist"}, mappedBy="newsletter")
     * @ORM\JoinColumn(name="newsletterGeo_id", referencedColumnName="id")
     */
    private $newsletterGeo;

    
    public function __construct()
    {
        $this->newsletterGeo = new ArrayCollection();
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

    public function getUnsubscribed(): ?int
    {
        return $this->unsubscribed;
    }

    public function setUnsubscribed(int $unsubscribed): self
    {
        $this->unsubscribed = $unsubscribed;

        return $this;
    }

    /**
     * @return Collection<int, NewsletterGeo>
     */
    public function getNewsletterGeo(): Collection
    {
        return $this->newsletterGeo;
    }

    public function addNewsletterGeo(NewsletterGeo $newsletterGeo): self
    {
        if (!$this->newsletterGeo->contains($newsletterGeo)) {
            $this->newsletterGeo[] = $newsletterGeo;
            $newsletterGeo->setNewsletter($this);
        }

        return $this;
    }

    public function removeNewsletterGeo(NewsletterGeo $newsletterGeo): self
    {
        if ($this->newsletterGeo->removeElement($newsletterGeo)) {
            // set the owning side to null (unless already changed)
            if ($newsletterGeo->getNewsletter() === $this) {
                $newsletterGeo->setNewsletter(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

}
