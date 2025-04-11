<?php

namespace App\Services;

class PharmaPlanTicketCreatedNotification extends BasePharmaPlanNotification
{
    protected function getBody(): string
    {
        return 'Você foi designado como responsável para um novo chamado.';
    }

    protected function getType(): string
    {
        return 'App\Notifications\TicketCreated';
    }
}
