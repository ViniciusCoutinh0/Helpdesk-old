<?php

namespace App\Services;

use App\Models\PharmaPlan\PharmaPlanNotification;
use App\Services\Contracts\PharmanPlanNotificationInterface;

abstract class BasePharmaPlanNotification implements PharmanPlanNotificationInterface
{
    protected const URL_PROD = 'http://prod.promofarma.int/projetos/public/tickets/';

    abstract protected function getType(): string;

    abstract protected function getBody(): string;

    protected function getTitle(array $payload): string
    {
        return sprintf('Novo Chamado: %s', $payload['name']);
    }

    protected function getActionUrl(array $payload): string
    {
        return sprintf('%s%d', self::URL_PROD, $payload['id'] ?? $payload['ticket_id']);
    }

    protected function getData(array $payload): array
    {
        return [
            'actions' => [
                [
                    "name" => "view",
                    "color" => null,
                    "event" => null,
                    "eventData" => [],
                    "emitDirection" => false,
                    "emitToComponent" => null,
                    "extraAttributes" => [],
                    "icon" => "lucide-eye",
                    "iconPosition" => null,
                    "isOutlined" => false,
                    "isDisabled" => false,
                    "label" => "Visualizar",
                    "shouldCloseNotification" => false,
                    "shouldOpenUrlInNewTab" => true,
                    "size" => null,
                    "url" => $this->getActionUrl($payload),
                    "view" => "notifications::actions.link-action"
                ]
            ],
            "body" => $this->getBody(),
            "duration" => "persistent",
            "icon" => null,
            "iconColor" => "secondary",
            "title" => $this->getTitle($payload),
            "view" => "notifications::notification",
            "viewData" => [],
            "format" => "filament"
        ];
    }

    protected function getNotifiableType(): string
    {
        return 'App\Models\User';
    }

    public function create(array $payload): void
    {
        (new PharmaPlanNotification)
            ->create([
                'id' => guidv4(),
                'type' => $this->getType(),
                'notifiable_type' => $this->getNotifiableType(),
                'notifiable_id' => $payload['responsible_id'],
                'data' => json_encode($this->getData($payload)),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }
}
