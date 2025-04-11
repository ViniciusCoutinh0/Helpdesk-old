<?php

namespace App\Services;

use App\Artia\Api;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Answer;
use App\Artia\Builder\QueryBuilder;
use App\Artia\Builder\MutationBuilder;

class Handler
{
    public static function createActivity(int $id, array $data, array $files = []): ?int
    {
        $description = "Detalhes do Chamado: " . $data['title'] . " \r\n";
        $description .= $data['description'] . "\r\n";
        $description .= "SETOR: {$data['section']} - ";
        $description .= "USUÁRIO HELPDESK: " . mb_convert_case($data['username'], MB_CASE_TITLE, 'UTF-8') . "\r\n";
        $description .= "\r\nMENSAGEM: \r\n";
        $description .= $data['message'] . "\r\n \r\n";

        if (count($files)) {
            $description .= "LINK ANEXO(S) ENVIADO(S) PELO USUÁRIO: \r\n";

            foreach ($files['files'] as $file) {
                $description .= defaultUrl() . $file['file_path'] . "\r\n";
            }
        }

        if (isset($data['fields'])) {
            $description .= "\r\nINFORMAÇÕES COMPLEMENTARES*: \r\n";

            foreach ($data['fields'] as $field) {
                $description .= mb_strtoupper($field['FIELD_NAME']) . ": " . mb_convert_case($field['FIELD_VALUE'], MB_CASE_TITLE, 'UTF-8') . "\r\n";
            }
        }

        if ($data['section'] === 'Lojas') {
            $description .= "SOLICITANTE: \r\n";
            $description .= $data['employee_name'] . "\r\n";
            $description .= "ACESSO REMOTO: \r\n";
            $description .= $data['computer'] . "\r\n";
        }

        return (new Api)
            ->name('createActivity')
            ->arguments([
                'title' => sprintf('[#%d] %s %s: %s', $id, $data['section'], $data['username'], $data['title']),
                'accountId' => (int) env('CONFIG_API_ACCOUNT_ID'),
                'folderId' => (int) $data['folder_id'],
                'description' => $description,
                'responsibleId' => $data['responsible'],
                'estimatedStart' => date('Y-m-d'),
                'estimatedEnd' => $data['estimated_end'],
                'actualStart' => date('Y-m-d'),
                'actualEnd' => '',
                'estimatedEffort' => floatval($data['estimated_effort']),
                'categoryText' => 'Chamado Integrado via API',
                'priority' => 100,
                'timeEstimatedStart' => date('H:i'),
                'timeEstimatedEnd' => '',
                'timeActualEnd' => '',
                'completedPercent' => 00.00
            ])
            ->body(['id'])
            ->build(new MutationBuilder)
            ->call()
            ->data
            ->createActivity
            ->id;
    }

    public static function createComment(int $id, string $message, array $files = []): ?object
    {
        $comment = "Integrado \r\n";
        $comment .= "*" . trim($message) . "\r\n \r\n";

        if (count($files)) {
            foreach ($files['files'] as $file) {
                $comment .= defaultUrl() . $file['file_path'] . "\r\n";
            }
        }

        return (new Api)
            ->name('createComment')
            ->arguments([
                'id' => $id,
                'accountId' => (int) env('CONFIG_API_ACCOUNT_ID'),
                'object' => 'activity',
                'content' => $comment,
            ])
            ->body([
                'id', 'content', 'createdAt',
                'author' => ['id', 'name', 'email'],
                'registeredBy' => ['id', 'name', 'email'],
                'users' => ['id', 'name', 'email'],
            ])
            ->build(new MutationBuilder)
            ->call();
    }

    public static function listingCommentsNotViewed(int $id): void
    {
        $ticket = (new Ticket())
            ->findBy($id)
            ->first();

        $response = (new Api)
            ->name('listingCommentsNotViewed')
            ->arguments([
                'ids' => [(int) $ticket->ID_ARTIA],
                'accountId' => (int) env('CONFIG_API_ACCOUNT_ID'),
                'type' => 'Activity',
                'viewed' => false,
            ])
            ->body([
                'id', 'content', 'createdAt', 'createdByApi',
                'author' => ['id', 'name', 'email'],
                'registeredBy' => ['id', 'name', 'email'],
                'users' => ['id', 'name', 'email'],
            ])
            ->build(new QueryBuilder)
            ->call();

        $answer = (new Answer());

        if (count($response->data->listingCommentsNotViewed)) {
            foreach ($response->data->listingCommentsNotViewed as $commit) {
                $identify = explode(' ', $commit->content);
                if (!in_array('Integrado', $identify)) {
                    $answer->TICKET_CHAMADO = (int) $ticket->TICKET_CHAMADO;
                    $answer->USUARIO = mb_convert_case($commit->author->name, MB_CASE_TITLE, 'UTF-8');
                    $answer->SETOR = 'Atendente (Via Artia)';
                    $answer->COMENTARIO = str_replace(';', '</br>', html_entity_decode($commit->content));
                    $answer->save();
                }
            }

            if (is_null($ticket->ATUALIZACAO)) {
                $ticket->ATUALIZACAO = date('Y-m-d H:i:s');
                $ticket->save();
            }
        }
    }
}
