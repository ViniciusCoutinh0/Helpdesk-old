<?php

namespace App\Models\Ticket;

use App\Layer\Layer;

class AttachmentAnswer extends Layer
{
    /**
     * Primary Key
     *
     * @var string
    */
    protected $table = 'TICKETS_ANEXOS_RESPOSTAS';

    /**
     * Primary Key
     *
     * @var string
    */
    protected $prefix = 'TICKET_ANEXO_RESPOSTA';

}


