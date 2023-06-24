<?php

namespace App\Crud;

use App\Entity\Address;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @implements CrudDto<Address>
 */
abstract class AddressDto implements CrudDto
{
    public ?string $city = null;

    public ?string $country = null;

    public function shouldPersist(): bool
    {
        return !empty($this->city) && !empty($this->country);
    }

    /**
     * The user must provide either both values, or none.
     */
    #[Assert\Callback]
    public function validateParent(ExecutionContextInterface $context, mixed $payload): void
    {
        if (!empty($this->city) && empty($this->country)) {
            $context->buildViolation('Country is required if City was set.')
                ->atPath('country')
                ->addViolation()
            ;
        }

        if (!empty($this->country) && empty($this->city)) {
            $context->buildViolation('City is required if Country was set.')
                ->atPath('city')
                ->addViolation()
            ;
        }
    }
}
