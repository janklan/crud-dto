<?php

namespace App\Crud;

use App\Entity\Address;

class AddressCreateDto extends AddressDto
{
    public function apply(): Address
    {
        return Address::createWith($this);
    }
}
