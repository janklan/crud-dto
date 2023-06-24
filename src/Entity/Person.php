<?php

namespace App\Entity;

use App\Crud\PersonCreateDto;
use App\Crud\PersonDto;
use App\Crud\PersonUpdateDto;
use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person implements CrudEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected string $name;

    /**
     * Pain point: the need to use cascading of entities in order to persist address, because the entity is potentially created in {@link updateSharedProperties()}.
     */
    #[ORM\OneToOne(cascade: ['persist', 'remove'], orphanRemoval: true)]
    protected ?Address $address = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'people')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?self $boss = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(mappedBy: 'boss', targetEntity: self::class)]
    private Collection $people;

    /**
     * @see self::createWith()
     */
    protected function __construct()
    {
        $this->people = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public static function createWith(PersonCreateDto $dto): self
    {
        $self = new self();
        $self->boss = $dto->boss;

        return $self->updateSharedProperties($dto);
    }

    public function updateWith(PersonUpdateDto $dto): self
    {
        // In a real-world scenario there could be some properties specific to PersonUpdateDto,
        // but not not PersonCreateDto, so they would be applied onto $this entity using this
        // method. After that, shared properties get updated, and done:
        return $this->updateSharedProperties($dto);
    }

    protected function updateSharedProperties(PersonDto $dto): self
    {
        $this->name = $dto->name;

        // Pain point: relying on the orphanRemoval to remove the address when it is set null.
        $this->address = $dto->address->shouldPersist() ? $dto->address->apply() : null;

        return $this;
    }

    public function getBoss(): ?self
    {
        return $this->boss;
    }

    public function setBoss(?self $boss): static
    {
        $this->boss = $boss;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(self $person): static
    {
        if (!$this->people->contains($person)) {
            $this->people->add($person);
            $person->setBoss($this);
        }

        return $this;
    }

    public function removePerson(self $person): static
    {
        if ($this->people->removeElement($person)) {
            // set the owning side to null (unless already changed)
            if ($person->getBoss() === $this) {
                $person->setBoss(null);
            }
        }

        return $this;
    }
}
