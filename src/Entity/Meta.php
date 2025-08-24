<?php

namespace App\Entity;

use App\Repository\MetaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MetaRepository::class)
 */
class Meta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $textFooter;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $entity;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idEntity;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEntity(): ?string
    {
        return $this->entity;
    }

    public function setEntity(?string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    public function getIdEntity(): ?int
    {
        return $this->idEntity;
    }

    public function setIdEntity(?int $idEntity): self
    {
        $this->idEntity = $idEntity;

        return $this;
    }

    public function getTextFooter(): ?string
    {
        return $this->textFooter;
    }

    public function setTextFooter(?string $textFooter): self
    {
        $this->textFooter = $textFooter;

        return $this;
    }
}
