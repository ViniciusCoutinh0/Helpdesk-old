<?php

namespace App\Models\Ticket;

use App\Layer\Instance\Db;
use App\Layer\Layer;

class Answer extends Layer
{
    /**
     * Table name in Database
     *
     * @var string
    */
    protected $table = 'TICKETS_RESPOSTAS';

    /**
     * Primary Key
     *
     * @var string
    */
    protected $prefix = 'TICKET_RESPOSTA';

    /**
     * @param App\Models\Ticket $ticket
     * @return null|array
     */
    public function getTicketResponses(Ticket $ticket): ?array
    {
        return $this->find('TICKETS_RESPOSTAS.*, TICKETS_ANEXOS_RESPOSTAS.TICKET_RESPOSTA, TICKETS_ANEXOS_RESPOSTAS.ENDERECO, Framework_Users.Avatar, Framework_Sectors.Name')
        ->left('TICKETS_ANEXOS_RESPOSTAS', 'TICKETS_ANEXOS_RESPOSTAS.TICKET_RESPOSTA', '=', 'TICKETS_RESPOSTAS.TICKET_RESPOSTA')
        ->left('Framework_Users', 'Framework_Users.Username', '=', 'TICKETS_RESPOSTAS.USUARIO')
        ->left('Framework_Sectors', 'Framework_Sectors.Framework_Sector', '=', 'Framework_Users.Framework_Sector')
        ->where(['TICKETS_RESPOSTAS.TICKET_CHAMADO' => $ticket->TICKET_CHAMADO])
        ->all();
    }

    /**
     * @param App\Models\Ticket $ticket
     * @param string $message
     * @param array $files
     * @return bool
    */
    public function createCommit(Ticket $ticket, string $message, array $files = []): bool
    {
        $commit = (new Answer());
        $commit->TICKET_CHAMADO = (int) $ticket->TICKET_CHAMADO;
        $commit->USUARIO = $ticket->USUARIO;
        $commit->SETOR = $ticket->SETOR;
        $commit->COMENTARIO = $message;
        $commit->save();

        if (count($files)) {
            $attachment = (new AttachmentAnswer());
            $attachment->TICKET_RESPOSTA = (int) Db::getInstance()->lastInsertId();
            $attachment->USUARIO = $ticket->USUARIO;
            $attachment->ENDERECO = null;

            foreach ($files['files'] as $file) {
                $attachment->ENDERECO .= $file['file_path'] . '&';
            }
            $attachment->save();
        }

        return true;
    }
}
