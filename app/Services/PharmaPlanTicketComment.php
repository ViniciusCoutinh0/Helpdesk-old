<?php

namespace App\Services;

use App\Models\Entity\Entity;
use App\Models\Entity\User;
use App\Models\PharmaPlan\PharmaPlanTicketComment as PharmaPlanTicketCommentModel;
use App\Models\PharmaPlan\PharmaPlanUser;
use App\Services\Contracts\PharmaPlanTicketCommentInterface;

class PharmaPlanTicketComment implements PharmaPlanTicketCommentInterface
{
    public function create(array $payload): void
    {
        $content = $this->getContent($payload);

        (new PharmaPlanTicketCommentModel)->create([
            'ticket_id' => $payload['ticket_id'],
            'user_id' => $this->getPharmaPlanUser(Session()->USER_ID),
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $payload['responsible_id'] = $this->getEntity($payload['responsible_id']);

        (new PharmaPlanTicketCommentedNotification)->create($payload);
    }

    private function getEntity(int $id): ?int
    {
        return (new Entity())
            ->find()
            ->where(['COD_PROCFIT' => $id])
            ->first()
            ?->ID_PHARMAPLAN;
    }

    private function getUserEmail(int $id): ?string
    {
        return (new User)
            ->findBy($id)
            ->first()
            ?->Email;
    }

    private function getPharmaPlanUser(int $id): ?int
    {
        return (new PharmaPlanUser)
            ->find()
            ->where(['email' => $this->getUserEmail($id)])
            ->first()
            ?->id;
    }

    private function getContent(array $payload)
    {
        $content = sprintf('<p>%s</p>', $payload['message']);

        if ($files = $payload['files']) {
            $content .= '<p>Anexos:</p>';
            $content .= $this->createLinksToAttachments($files);
        }

        return $content;
    }

    private function createLinksToAttachments(array $items): string
    {
        $links = array_map(function (array $item): string {
            return sprintf('<a href="%s">Anexo</a>', asset($item['file_path']));
        }, $items['files']);

        return implode('<p>', $links);
    }
}
