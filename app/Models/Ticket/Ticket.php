<?php

namespace App\Models\Ticket;

use App\Artia\Api;
use App\Layer\Instance\Db;
use App\Layer\Layer;
use App\Models\Entity\User;

class Ticket extends Layer
{
    /**
     * Table name in Database
     *
     * @var string
     */
    protected $table = 'TICKETS_CHAMADOS';

    /**
     * Primary Key
     *
     * @var string
     */
    protected $prefix = 'TICKET_CHAMADO';

    /**
     * Files valid for upload
     *
     * @var array
     */
    protected $isValid = [
        'image/jpeg',
        'image/png',
        'image/bmp',
        'image/x-bmp',
        'application/pdf',
        'application/vnd.ms-excel',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];


    /**
     * @param App\Models\Entity\User $user
     * @param mixed $top
     * @param int $state
     * @return null|array
     */
    public function getTicketsByUsernameAndState(User $user, $top = 8, int $state = 1): ?array
    {
        return $this->find(($top ? 'TOP ' . $top . ' *' : '*'))
            ->where(['USUARIO' => $user->Username, 'ESTADO' => $state])
            ->orderBy('INICIALIZACAO', 'DESC')
            ->all();
    }

    public function getAllTicketsByResponsableId(): ?array
    {
        return $this->find('TICKETS_CHAMADOS.*, USUARIOS_ARTIA.USUARIO_ARTIA')
            ->join('USUARIOS_ARTIA', 'USUARIOS_ARTIA.COD_PROCFIT', '=', 'TICKETS_CHAMADOS.RESPONSAVEL_ARTIA')
            ->where(['ESTADO' => 1])
            ->all();
    }

    /**
     * @param int $id
     * @return null|object
     */
    public function getTicketById(int $id): ?object
    {
        return $this->findBy($id, 'TICKETS_CHAMADOS.* , Framework_Users.* , USUARIOS.NOME AS PROC_NOME')
            ->join('USUARIOS', 'USUARIOS.USUARIO', '=', 'TICKETS_CHAMADOS.RESPONSAVEL_ARTIA')
            ->join('Framework_Users', 'Framework_Users.Username', '=', 'TICKETS_CHAMADOS.USUARIO')
            ->first();
    }

    /**
     * @param array $data
     * @param array $files
     * @return null|int
     */
    public function createTicket(array $data, array $files = []): ?int
    {
        $encode = ['MESSAGE' => html_entity_decode($data['message']), 'DESCRIPTION' => html_entity_decode($data['description'])];

        if (isset($data['fields'])) {
            $encode['FIELDS'] = $data['fields'];
        }

        $column = (new Ticket());
        $column->USUARIO = $data['username'];
        $column->SETOR = html_entity_decode($data['section']);
        $column->TITULO = html_entity_decode($data['title']);
        $column->MENSAGEM = json_encode($encode, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $column->NUMERO_BALCONISTA = $data['employee_number'];
        $column->NOME_BALCONISTA = html_entity_decode($data['employee_name']);
        $column->COMPUTADOR = html_entity_decode($data['computer']);
        $column->DEPARTAMENTO = $data['departament'];
        $column->CATEGORIA = $data['category'];
        $column->SUB_CATEGORIA = $data['subcategory'];
        $column->ID_ARTIA = 0; // UPDATE AFTER
        $column->ID_FOLDER = $data['folder_id'];
        $column->RESPONSAVEL_ARTIA = $data['responsible_id'];
        $column->ESFORCO_ARTIA = $data['estimated_effort'];
        $column->PLANTAO = $data['on_duty'];
        $column->CHAMADO_RAPIDO = 'D';
        $column->PRAZO_ARTIA = $data['estimated_end'];

        if ($column->save()) {
            $id = Db::getInstance()->lastInsertId();

            if (count($files)) {
                $attachment = (new Attachment());

                foreach ($files['files'] as $file) {
                    $attachment->TICKET_CHAMADO = $id;
                    $attachment->USUARIO = $data['username'];
                    $attachment->ENDERECO = $file['file_path'];
                    $attachment->save();
                }
            }

            return $id;
        }
        return null;
    }

    /**
     * @param int $id
     * @param int $id_artia
     * @return null|bool
     */
    public function updateArtiaIdByTicketId(int $id, int $id_artia): ?bool
    {
        $ticket = (new Ticket())->findBy($id)->first();

        if (!$ticket) {
            return null;
        }

        $ticket->TICKET_CHAMADO = $id;
        $ticket->ID_ARTIA = $id_artia;
        return $ticket->save();
    }

    public function getAllTicketsByBetween(array $data): ?array
    {
        return $this->find('TICKETS_CHAMADOS.* , USUARIOS.NOME USUARIO_PROC')
            ->join('USUARIOS', 'USUARIOS.USUARIO', '=', 'TICKETS_CHAMADOS.RESPONSAVEL_ARTIA')
            ->where(['TICKETS_CHAMADOS.DEPARTAMENTO' => $data['departament']])
            ->andWhere(
                'CONVERT(DATE, TICKETS_CHAMADOS.INICIALIZACAO)',
                sprintf('\'%s\' AND \'%s\'', $data['start_date'], $data['end_date']),
                'BETWEEN'
            )
            ->all();
    }
}
