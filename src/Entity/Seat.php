<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeatRepository::class)]
class Seat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $seat_number = null;

    #[ORM\Column]
    private ?bool $occupied = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeatNumber(): ?int
    {
        return $this->seat_number;
    }

    public function setSeatNumber(int $seat_number): self
    {
        $this->seat_number = $seat_number;

        return $this;
    }

    public function isOccupied(): ?bool
    {
        return $this->occupied;
    }

    public function setOccupied(bool $occupied): self
    {
        $this->occupied = $occupied;

        return $this;
    }
}
