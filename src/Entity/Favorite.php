<?php
// src/Entity/Favorite.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FavoriteRepository")
 */
class Favorite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Annonces")
     * @ORM\JoinColumn(name="annonces_id", referencedColumnName="id")
     */
    private $annonce;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set annonce
     *
     * @param \App\Entity\Annonces $annonce
     *
     * @return Favorite
     */
    public function setAnnonce(\App\Entity\Annonces $annonce = null)
    {
        $this->annonce = $annonce;

        return $this;
    }

    /**
     * Get annonce
     *
     * @return \App\Entity\Annonces
     */
    public function getAnnonce()
    {
        return $this->annonce;
    }

    /**
     * Set user
     *
     * @param \App\Entity\User $user
     *
     * @return Favorite
     */
    public function setUser(\App\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \App\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
