<?php

namespace App\Crud;

use App\Entity\Person;

class PersonCreateDto extends PersonDto
{
    public function __construct(
        /**
         * Business logic commands we're not allowed to change the boss after the Person is created.
         *
         * This is just a proof of concept of defining properties, most often associations, that you don't want allow changing later.
         *
         * Another reason is to demonstrate that relations don't have to be represented as a DTO when we're dealing with
         * a single value: we don't need to update the bosses' details when creating a new Person. That's not the case
         * with the Address, though. That one needs a DTO. In other words, whenever you want to create or update something
         * using a form, you need a DTO for it. If you want to read something, you don't need a DTO.
         */
        public ?Person $boss = null
    ) {
        $this->address = new AddressCreateDto();
    }

    public function apply(): Person
    {
        return Person::createWith($this);
    }
}
