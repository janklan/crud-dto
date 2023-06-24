<?php

namespace App\Crud;

use App\Entity\Person;

class PersonUpdateDto extends PersonDto
{
    public function __construct(private readonly Person $source)
    {
        $this->name = $this->source->getName();

        // Pain point 1: manual creation of related DTO

        // Pain point 2: PersonUpdateDto is responsible for business decision: create a new address,
        // or update an existing one? It doesn't feel right to give a "data transfer object" this power.
        if ($address = $this->source->getAddress()) {
            $this->address = new AddressUpdateDto($address);
        } else {
            $this->address = new AddressCreateDto();
        }
    }

    public function apply(): Person
    {
        return $this->source->updateWith($this);
    }
}
