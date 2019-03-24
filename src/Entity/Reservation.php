<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reservation_key;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ticket_path;

    /**
     * @ORM\Column(type="datetime")
     */
    private $reservation_date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservationKey(): ?string
    {
        return $this->reservation_key;
    }

    public function setReservationKey(string $reservation_key): self
    {
        $this->reservation_key = $reservation_key;

        return $this;
    }

    public function getTicketPath(): ?string
    {
        return $this->ticket_path;
    }

    public function setTicketPath(string $ticket_path): self
    {
        $this->ticket_path = $ticket_path;

        return $this;
    }

    public function getReservationDate(): ?\DateTimeInterface
    {
        return $this->reservation_date;
    }

    public function setReservationDate(\DateTimeInterface $reservation_date): self
    {
        $this->reservation_date = $reservation_date;

        return $this;
    }
}
