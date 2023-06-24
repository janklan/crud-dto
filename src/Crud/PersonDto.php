<?php

namespace App\Crud;

use App\Entity\Person;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @implements CrudDto<Person>
 */
abstract class PersonDto implements CrudDto
{
    /**
     * The name must be always set and can be changed.
     */
    #[Assert\NotBlank]
    public string $name;

    /**
     * The address may be created at any time, and updated after that at any time.
     *
     * Prototype requirement: The address should be part of the Person form.
     */
    #[Assert\Valid]
    public AddressDto $address;
}
