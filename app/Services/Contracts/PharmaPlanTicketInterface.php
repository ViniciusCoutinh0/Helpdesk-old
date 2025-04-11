<?php

namespace App\Services\Contracts;

interface PharmaPlanTicketInterface
{
    public function create(array $payload): int;
}
