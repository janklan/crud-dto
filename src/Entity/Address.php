<?php

namespace App\Entity;

use App\Crud\AddressCreateDto;
use App\Crud\AddressUpdateDto;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address implements CrudEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $city = null;

    #[ORM\Column(length: 255)]
    protected ?string $country = null;

    /**
     * @see self::createWith()
     */
    protected function __construct()
    {
    }

    public function __toString(): string
    {
        return $this->city.', '.$this->country;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public static function createWith(AddressCreateDto $dto): self
    {
        $self = new self();
        $self->city = $dto->city;
        $self->country = $dto->country;

        return $self;
    }

    public function updateWith(AddressUpdateDto $dto): self
    {
        $this->city = $dto->city;
        $this->country = $dto->country;

        return $this;
    }
}
