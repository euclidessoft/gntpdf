<?php

namespace App\Entity;

use App\Repository\NumeroRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:NumeroRepository::class) ]
class Numero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
