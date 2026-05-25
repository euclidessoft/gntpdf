<?php
namespace App\Service;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class twigService extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('json_decode', [$this, 'jsonDecode']),
        ];
    }

    public function jsonDecode(string $json): array
    {
        return json_decode($json, true);
    }
}
