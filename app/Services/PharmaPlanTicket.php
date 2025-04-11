<?php

namespace App\Services;

use App\Models\PharmaPlan\PharmaPlanTicket as PharmaPlanTicketModel;
use App\Models\Entity\Entity;
use App\Models\PharmaPlan\PharmaPlanUser;
use App\Services\Contracts\PharmaPlanTicketInterface;

class PharmaPlanTicket implements PharmaPlanTicketInterface
{
    private const FALLBACK_OWNER_ID = 1;

    private const FALLBACK_RESPONSIBLE_ID = 1;

    private const DEFAULT_TYPE_ID = 1;

    private const DEFAULT_STATUS_ID = 1;

    private const DEFAULT_PRIORITY_ID = 3;

    private const STORE_SECTOR = 'Lojas';

    public function create(array $payload): int
    {
        $payload = $this->getPayload($payload);

        $ticketId = (new PharmaPlanTicketModel())->create($payload);

        (new PharmaPlanTicketCreatedNotification)->create(array_merge(['id' => $ticketId], $payload));

        return $ticketId;
    }

    private function getPharmaPlanUser(string $email): ?int
    {
        return (new PharmaPlanUser())
            ->find()
            ->where(['email' => $email])
            ->first()
            ?->id;
    }

    private function getEntity(int $id): ?int
    {
        return (new Entity())
            ->find()
            ->where(['COD_PROCFIT' => $id])
            ->first()
            ?->ID_PHARMAPLAN;
    }

    private function getPayload(array $payload): array
    {
        $now = date('Y-m-d H:i:s');

        return [
            'name' => sprintf('[#%d] %s', $payload['id'], $payload['title']),
            'content' => $this->getContent($payload),
            'owner_id' => $this->getPharmaPlanUser($payload['owner_email']) ?? self::FALLBACK_OWNER_ID,
            'responsible_id' => $this->getEntity($payload['responsible_id']) ?? self::FALLBACK_RESPONSIBLE_ID,
            'project_id' => $payload['project_id'],
            'estimated_start' => date('Y-m-d H:i:s'),
            'estimated_end' => $payload['estimated_end'],
            'code' => sprintf('HELPDESK-%s', $payload['id']),
            'payload' => $payload['id'],
            'status_id' => self::DEFAULT_STATUS_ID,
            'type_id' => self::DEFAULT_TYPE_ID,
            'priority_id' => self::DEFAULT_PRIORITY_ID,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    private function getContent(array $data): string
    {
        return <<<HTML
            <p>{$data['message']}</p>
            <p>{$this->getRequesterName($data)}</p>
            {$this->getCustomFields($data)}
            {$this->getFilesAction($data)}
        HTML;
    }

    private function getCustomFields(array $data): ?string
    {
        $fields = isset($data['fields']) ? $data['fields'] : [];

        $fields = array_map(function (array $field): string {
            return sprintf('<li>%s: <strong>%s</strong></li>', ucfirst(trim($field['FIELD_NAME'])), trim($field['FIELD_VALUE']));
        }, $fields);

        return $fields ? sprintf('<p>Informações complementares:</p> <ul>%s</ul>', implode(' ', $fields)) : null;
    }

    private function getFilesAction(array $data): ?string
    {
        return $this->hasFiles($data) ? '<a href="#" @click.prevent="$dispatch(\'tabselected\', 2)">Visualizar anexo(s)</a>' : null;
    }

    private function getRequesterName(array $data): ?string
    {
        return $this->isSectorIsStore($data) ? sprintf('Solicitado por: %s', $data['employee_name']) : null;
    }

    private function hasFiles(array $data): bool
    {
        return isset($data['files']);
    }

    private function isSectorIsStore(array $data): bool
    {
        return $data['section'] == self::STORE_SECTOR;
    }
}
