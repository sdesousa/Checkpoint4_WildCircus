<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpectacleRepository")
 * @Vich\Uploadable
 */
class Spectacle
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Champ obligatoire")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Entrée trop longue, elle doit être au plus {{ limit }} caractères")
     */
    private $place;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Champ obligatoire")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Champ obligatoire")
     * @Assert\Positive
     */
    private $capacity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $poster;

    /**
     * @Vich\UploadableField(mapping="spectacle_picture", fileNameProperty="poster")
     * @var File|null
     * @Assert\File(
     *     maxSize = "300k",
     *     maxSizeMessage="La taille des images est limité à {{ limit }} {{ suffix }}",
     *     mimeTypes = {"image/jpeg", "image/png", "image/webp", "image/gif"},
     *     mimeTypesMessage = "Ce n'est pas un format d'image valide"
     * )
     */
    private $posterFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Act", mappedBy="spectacles")
     */
    private $acts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="spectacle", orphanRemoval=true)
     */
    private $bookings;

    public function __construct()
    {
        $this->acts = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $posterFile
     */
    public function setPosterFile(?File $posterFile = null): void
    {
        $this->posterFile = $posterFile;

        if (null !== $posterFile) {
            $this->updatedAt = new DateTime();
        }
    }

    public function getPosterFile(): ?File
    {
        return $this->posterFile;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Collection|Act[]
     */
    public function getActs(): Collection
    {
        return $this->acts;
    }

    public function addAct(Act $act): self
    {
        if (!$this->acts->contains($act)) {
            $this->acts[] = $act;
            $act->addSpectacle($this);
        }

        return $this;
    }

    public function removeAct(Act $act): self
    {
        if ($this->acts->contains($act)) {
            $this->acts->removeElement($act);
            $act->removeSpectacle($this);
        }

        return $this;
    }

    public function getActsToString(): string
    {
        $actNames = [];
        foreach ($this->getActs() as $act) {
            $actNames[] = $act->getName();
        }
        return implode(', ', $actNames);
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setSpectacle($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getSpectacle() === $this) {
                $booking->setSpectacle(null);
            }
        }

        return $this;
    }
}
