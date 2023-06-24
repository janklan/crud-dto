<?php

namespace App\Crud;

use App\Entity\Address;

class AddressUpdateDto extends AddressDto
{
    public function __construct(private readonly Address $source)
    {
        $this->city = $this->source->getCity();
        $this->country = $this->source->getCountry();
    }

    public function apply(): Address
    {
        return $this->source->updateWith($this);
    }
}
