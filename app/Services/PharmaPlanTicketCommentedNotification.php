<?php

namespace App\Services;

class PharmaPlanTicketCommentedNotification extends BasePharmaPlanNotification
{
    protected function getBody(): string
    {
        return 'Um novo comentário foi adicionado a este chamado.';
    }

    protected function getTitle(array $payload): string
    {
        return 'Novo Comentário';
    }

    protected function getType(): string
    {
        return 'App\Notifications\TicketCommented';
    }
}
