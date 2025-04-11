<?php

use App\Artia\Api;
use App\Models\Entity\Entity;
use App\Models\Ticket\Ticket;
use App\Artia\Builder\QueryBuilder;

require 'console.php';

$api = new Api;

$tickets = (new Ticket)->find()
    ->where(['ESTADO' => 1])
    ->orderBy('TICKET_CHAMADO', 'DESC')
    ->all();

foreach ($tickets as $ticket) {
    if ((int) $ticket->ID_ARTIA === 0) {
        continue;
    }

    $call = $api->name('showActivity')
        ->arguments([
            'id' => (int) $ticket->ID_ARTIA,
            'accountId' => (int) env('CONFIG_API_ACCOUNT_ID'),
            'folderId' => (int) $ticket->ID_FOLDER,
        ])
        ->body([
            'id',
            'actualEnd',
            'timeActualEnd',
            'responsible' => ['id', 'name'],
            'customStatus' => ['id', 'statusName', 'status'],
        ])
        ->build(new QueryBuilder)
        ->call();

    if ($call->data === null) {
        $ticket->ERROR = 'S';
        $ticket->save();
        // $ticket->destroy();
        continue;
    }

    $activity = $call->data->showActivity;

    $status = $activity->customStatus;

    $responsible = $activity->responsible;

    $ticket->ESTADO_ARTIA = trim($status->statusName);

    if (trim($status->statusName) === 'Encerrado') {
        $ticket->ESTADO = 2;
        $ticket->FINALIZACAO_ARTIA = date('Y-m-d H:i:s', strtotime(
            sprintf('%s %s', trim($activity->actualEnd), trim($activity->timeActualEnd))
        ));
    }

    $activityUser = (new Entity)->find()
        ->where(['USUARIO_ARTIA' => $responsible->id])
        ->first();

    if ($ticket->RESPONSAVEL_ARTIA != $activityUser->USUARIO_ARTIA) {
        $ticket->RESPONSAVEL_ARTIA = $activityUser->COD_PROCFIT;
    }

    $ticket->ATUALIZACAO = date('Y-m-d H:i:s');

    $ticket->save();
var_dump($ticket->ID_ARTIA);
}
