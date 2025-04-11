<?php

namespace App\Models\Ticket;

use App\Layer\Layer;

class Attachment extends Layer
{
    /**
     * Table name in Database
     *
     * @var string
    */
    protected $table = 'TICKETS_ANEXOS';

    /**
     * Primary Key
     *
     * @var string
    */
    protected $prefix = 'TICKET_ANEXO';

    /**
     * @param App\Models\Ticket 
     * @return null|array
     */
    public function getAttachmentById(Ticket $ticket): ?array
    {
        return $this->find()->where(['TICKET_CHAMADO' => $ticket->TICKET_CHAMADO])->all();
    }
}
