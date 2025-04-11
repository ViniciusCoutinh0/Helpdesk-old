<?php

namespace App\Services\Contracts;

interface PharmaPlanMediaInterface
{
    public function create(int $id, array $files): void;
}
