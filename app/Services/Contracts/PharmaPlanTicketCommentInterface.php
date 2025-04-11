<?php

namespace App\Services\Contracts;

interface PharmaPlanTicketCommentInterface
{
    public function create(array $payload): void;
}
