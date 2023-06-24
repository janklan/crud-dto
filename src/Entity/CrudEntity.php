<?php

namespace App\Entity;

interface CrudEntity extends \Stringable
{
    public function getId(): ?int;
}
