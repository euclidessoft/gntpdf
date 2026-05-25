<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $stg = null;

    #[ORM\Column]
    private ?int $intg = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bln = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStg(): ?string
    {
        return $this->stg;
    }

    public function setStg(string $stg): static
    {
        $this->stg = $stg;

        return $this;
    }

    public function getIntg(): ?int
    {
        return $this->intg;
    }

    public function setIntg(int $intg): static
    {
        $this->intg = $intg;

        return $this;
    }

    public function isBln(): ?bool
    {
        return $this->bln;
    }

    public function setBln(?bool $bln): static
    {
        $this->bln = $bln;

        return $this;
    }
}
