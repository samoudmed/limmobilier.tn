<?php

// src/Service/InspectorService.php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\BannedIp;

class InspectorService
{
    private EntityManagerInterface $em;
    private array $patterns = [
        '/\.env/',
        '/\.git/',
        '/\.gitlab-ci\.yml/',
        '/\.php$/',
    ];

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function isUrlSuspicious(string $url): bool
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }
        return false;
    }

    public function banIp(string $ip): void
    {
        // Vérifie si IP déjà bannie
        $repo = $this->em->getRepository(BannedIp::class);
        $existing = $repo->find($ip);
        if (!$existing) {
            $bannedIp = new BannedIp($ip);
            $this->em->persist($bannedIp);
            $this->em->flush();
        }
    }

    public function isIpBanned(string $ip): bool
    {
        return (bool) $this->em->getRepository(BannedIp::class)->find($ip);
    }
}

