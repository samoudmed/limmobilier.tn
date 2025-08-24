<?php
// src/App/Entity/types.php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="types")
 */

class Types
{
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
     * @ORM\Column(type="integer")
     */
    private $is_published;

    
    public function __toString()
    {
        return $this->label;
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

    public function getIsPublished(): ?int
    {
        return $this->is_published;
    }

    public function setIsPublished(int $is_published): self
    {
        $this->is_published = $is_published;

        return $this;
    }
    
}
