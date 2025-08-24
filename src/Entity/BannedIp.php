<?php

// src/Entity/BannedIp.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="banned_ips")
 */
class BannedIp
{
    /** 
     * @ORM\Id 
     * @ORM\Column(type="string", length=45) 
     */
    private string $ip;

    /** @ORM\Column(type="datetime") */
    private \DateTimeInterface $bannedAt;

    public function __construct(string $ip)
    {
        $this->ip = $ip;
        $this->bannedAt = new \DateTimeImmutable();
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getBannedAt(): \DateTimeInterface
    {
        return $this->bannedAt;
    }
}
