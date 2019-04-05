<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TripRepository")
 */
class Trip
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
    private $departure_place;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $arrival_place;

    /**
     * @ORM\Column(type="datetime")
     */
    private $departure_schedule;

    /**
     * @ORM\Column(type="time")
     */
    private $duration;

    /**
     * @ORM\Column(type="boolean")
     */
    private $canceled;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbr_places;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conductor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="trip", orphanRemoval=true)
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Feedback", mappedBy="trip", orphanRemoval=true)
     */
    private $feedbacks;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->feedbacks = new ArrayCollection();
        $this->canceled = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeparturePlace(): ?string
    {
        return $this->departure_place;
    }

    public function setDeparturePlace(string $departure_place): self
    {
        $this->departure_place = $departure_place;

        return $this;
    }

    public function getArrivalPlace(): ?string
    {
        return $this->arrival_place;
    }

    public function setArrivalPlace(string $arrival_place): self
    {
        $this->arrival_place = $arrival_place;

        return $this;
    }

    public function getDepartureSchedule(): ?\DateTimeInterface
    {
        return $this->departure_schedule;
    }

    public function setDepartureSchedule(\DateTimeInterface $departure_schedule): self
    {
        $this->departure_schedule = $departure_schedule;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCanceled(): ?bool
    {
        return $this->canceled;
    }

    public function setCanceled(bool $canceled): self
    {
        $this->canceled = $canceled;

        return $this;
    }

    public function getNbrPlaces(): ?int
    {
        return $this->nbr_places;
    }

    public function setNbrPlaces(int $nbr_places): self
    {
        $this->nbr_places = $nbr_places;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getConductor(): ?User
    {
        return $this->conductor;
    }

    public function setConductor(?User $conductor): self
    {
        $this->conductor = $conductor;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setTrip($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getTrip() === $this) {
                $reservation->setTrip(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Feedback[]
     */
    public function getFeedbacks(): Collection
    {
        return $this->feedbacks;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedbacks->contains($feedback)) {
            $this->feedbacks[] = $feedback;
            $feedback->setTrip($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedbacks->contains($feedback)) {
            $this->feedbacks->removeElement($feedback);
            // set the owning side to null (unless already changed)
            if ($feedback->getTrip() === $this) {
                $feedback->setTrip(null);
            }
        }

        return $this;
    }

    public function remainingPlaces():int
    {
        return $this->nbr_places - sizeof($this->reservations);
    }
}
