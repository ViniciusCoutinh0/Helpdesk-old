<?php

namespace App\Services\Contracts;

interface PharmanPlanNotificationInterface
{
    public function create(array $payload): void;
}
