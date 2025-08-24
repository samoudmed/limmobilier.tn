<?php

// src/App/Entity/Reponses.php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReponsesRepository")
 * @ORM\Table(name="reponses")
 */
class Reponses {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="integer")
     */
    private $statut;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $is_deleted;
    
    /**
     * @ORM\ManyToOne(targetEntity="Message")
     * @ORM\JoinColumn(name="frist_message_id", referencedColumnName="id")
     */
    private $first_message;
    
    /**
     * @ORM\ManyToOne(targetEntity="Message")
     * @ORM\JoinColumn(name="previous_message_id", referencedColumnName="id")
     */
    private $previous_message;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity="Sender")
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     */
    private $receiver;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

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
     * Set message
     *
     * @param string $message
     *
     * @return Reponses
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     *
     * @return Reponses
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return integer
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set isDeleted
     *
     * @param integer $isDeleted
     *
     * @return Reponses
     */
    public function setIsDeleted($isDeleted)
    {
        $this->is_deleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return integer
     */
    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Reponses
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Reponses
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set firstMessage
     *
     * @param \App\Entity\Message $firstMessage
     *
     * @return Reponses
     */
    public function setFirstMessage(\App\Entity\Message $firstMessage = null)
    {
        $this->first_message = $firstMessage;

        return $this;
    }

    /**
     * Get firstMessage
     *
     * @return \App\Entity\Message
     */
    public function getFirstMessage()
    {
        return $this->first_message;
    }

    /**
     * Set previousMessage
     *
     * @param \App\Entity\Message $previousMessage
     *
     * @return Reponses
     */
    public function setPreviousMessage(\App\Entity\Message $previousMessage = null)
    {
        $this->previous_message = $previousMessage;

        return $this;
    }

    /**
     * Get previousMessage
     *
     * @return \App\Entity\Message
     */
    public function getPreviousMessage()
    {
        return $this->previous_message;
    }

    /**
     * Set sender
     *
     * @param \App\Entity\User $sender
     *
     * @return Reponses
     */
    public function setSender(\App\Entity\User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \App\Entity\User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param \App\Entity\User $receiver
     *
     * @return Reponses
     */
    public function setReceiver(\App\Entity\Sender $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return \App\Entity\User
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
}
