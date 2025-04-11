<?php

namespace App\Models\Ticket;

use App\Layer\Layer;

class Rating extends Layer
{
    protected $table = 'TICKETS_AVALIACAOS';

    protected $prefix = 'TICKET_AVALIACAO';

    /**
     * @param int $id
     * @return null|object
    */
    public function getRating(int $id): ?object
    {
        return $this->find()->where(['TICKET_CHAMADO' => $id])->first();
    }
}
