<?php

use App\Models\Entity\Entity;
use App\Models\Ticket\Ticket;
use App\Services\Handler;

require 'console.php';

$tickets = (new Ticket)->find()
    ->where(['ID_ARTIA' => 0])
    ->all();

if ($tickets === null) {
    return 0;
}

foreach ($tickets as $ticket) {

    $user = (new Entity)->find()
        ->where(['COD_PROCFIT' => $ticket->RESPONSAVEL_ARTIA])
        ->first();

    $decode = json_decode($ticket->MENSAGEM, true);

    $data = [
        'title' => $ticket->TITULO,
        'description' => $decode['DESCRIPTION'],
        'username' => $ticket->USUARIO,
        'message' => $decode['MESSAGE'],
        'fields' => $decode['FIELDS'] ?? [],
        'section' => $ticket->SETOR,
        'employee_name' => $ticket->NOME_BALCONISTA,
        'computer' => $ticket->COMPUTADOR,
        'responsible' => (int) $user->USUARIO_ARTIA,
        'folder_id' => $ticket->ID_FOLDER,
        'estimated_effort' => floatval($ticket->ESFORCO_ARTIA),
        'estimated_end' => date('Y-m-d H:i', strtotime('+4 days')),
    ];

    $activityId = Handler::createActivity($ticket->TICKET_CHAMADO, $data, []);

    $ticket->ID_ARTIA = $activityId;
    $ticket->save();
}
