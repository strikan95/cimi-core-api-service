<?php
namespace App\PropertyListing\Entity;

use App\Amenity\Entity\Amenity as AmenityEntity;
use App\AppUser\Entity\AppUser as AppUserEntity;
use  App\PropertyListing\Repository\PropertyListingRepository;
use App\Reservation\Entity\Reservation as ReservationEntity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyListingRepository::class)]
class PropertyListing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(precision: 6)]
    private ?float $lat = null;

    #[ORM\Column(precision: 6)]
    private ?float $lon = null;

    #[ORM\ManyToOne(targetEntity: AppUserEntity::class, inversedBy: 'listings')]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id')]
    private AppUserEntity|null $owner = null;

    #[ORM\ManyToMany(targetEntity: AmenityEntity::class, inversedBy: 'listings')]
    #[ORM\JoinTable(name: 'listings_amenities')]
    private Collection $amenities;

    #[ORM\OneToMany(mappedBy: 'listing', targetEntity: ReservationEntity::class, cascade: ['persist'])]
    private Collection $reservations;

    #[ORM\Column(name: "createdAt", type: "datetime")]
    private ?DateTime $createdAt = null;

    public function __construct()
    {
        $this->amenities = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function setAmenities(Collection $amenities): void
    {
        $this->amenities = $amenities;
    }

    public function addAmenity(AmenityEntity $amenity): self {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities[] = $amenity;
        }

        return $this;
    }

    public function removeAmenity(AmenityEntity $amenity): self{
        if ($this->amenities->contains($amenity)) {
            $this->amenities->removeElement($amenity);
        }

        return $this;
    }

    public function getOwner(): AppUserEntity
    {
        return $this->owner;
    }

    public function setOwner(AppUserEntity $owner): void
    {
        $this->owner = $owner;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): void
    {
        $this->lat = $lat;
    }


    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function setLon(?float $lon): void
    {
        $this->lon = $lon;
    }

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function setReservations(Collection $reservations): void
    {
        $this->reservations = $reservations;
    }

    public function addReservation(ReservationEntity $reservation): self {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setListing($this);
        }

        return $this;
    }

    public function removeReservation(ReservationEntity $reservation): self{
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
        }

        return $this;
    }
}
