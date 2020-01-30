<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Champ obligatoire")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Entrée trop longue, elle doit être au plus {{ limit }} caractères")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Positive
     */
    private $numberTicket;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalPrice;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Spectacle", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $spectacle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNumberTicket(): ?int
    {
        return $this->numberTicket;
    }

    public function setNumberTicket(int $numberTicket): self
    {
        $this->numberTicket = $numberTicket;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getSpectacle(): ?Spectacle
    {
        return $this->spectacle;
    }

    public function setSpectacle(?Spectacle $spectacle): self
    {
        $this->spectacle = $spectacle;

        return $this;
    }
}
