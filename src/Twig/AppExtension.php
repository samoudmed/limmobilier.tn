<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('html_entity', [$this, 'html_entity']),
        ];
    }

    public function html_entity(string $string): string
    {
        $hstring =  html_entity_decode($string);
        
        return $hstring;
    }
}