<?php

namespace App\Crud;

use App\Entity\CrudEntity;

/**
 * @template T of CrudEntity
 */
interface CrudDto
{
    /** @return T */
    public function apply(): CrudEntity;
}
