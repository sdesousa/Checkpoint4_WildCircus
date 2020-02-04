<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PriceRepository")
 */
class Price
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $kid;

    /**
     * @ORM\Column(type="integer")
     */
    private $adult;

    /**
     * @ORM\Column(type="integer")
     */
    private $senior;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKid(): ?int
    {
        return $this->kid;
    }

    public function setKid(int $kid): self
    {
        $this->kid = $kid;

        return $this;
    }

    public function getAdult(): ?int
    {
        return $this->adult;
    }

    public function setAdult(int $adult): self
    {
        $this->adult = $adult;

        return $this;
    }

    public function getSenior(): ?int
    {
        return $this->senior;
    }

    public function setSenior(int $senior): self
    {
        $this->senior = $senior;

        return $this;
    }

    public function toArray()
    {
        return [
            'kid' => $this->kid,
            'adult' => $this->adult,
            'senior' => $this->senior,
        ];
    }
}
