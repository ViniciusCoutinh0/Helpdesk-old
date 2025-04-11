<?php

namespace App\Services;

use App\Models\Entity\User;
use App\Models\PharmaPlan\PharmaPlanMedia as PharmaPlanMediaModel;
use App\Models\PharmaPlan\PharmaPlanUser;
use App\Services\Contracts\PharmaPlanMediaInterface;

class PharmaPlanMedia implements PharmaPlanMediaInterface
{
    private const DIRNAME = 'url';

    public function create(int $id, array $items): void
    {
        if (!isset($items['files'])) {
            return;
        }

        foreach ($items['files'] as $item) {
            (new PharmaPlanMediaModel)->create($this->getPayload($id, $item));
        }
    }

    private function getPayload(int $id, array $item): array
    {
        return [
            'filename' => $item['file_name'],
            'extension' => $item['file_extension'],
            'mime_type' => $item['file_mime'],
            'path' => asset($item['file_path']),
            'size' => $this->getFileSize($item['file_path']),
            'dirname' => self::DIRNAME,
            'user_id' => $this->getPharmaPlanUser(Session()->USER_ID),
            'ticket_id' => $id,
            'created_at' =>  date('Y-m-d H:i:s'),
        ];
    }

    private function getFileSize(string $filename): int
    {
        return filesize(sprintf('%s/../../%s', __DIR__, $filename)) ?? 0;
    }

    private function getUserEmail(int $id): ?string
    {
        return (new User())
            ->findBy($id)
            ->first()
            ?->Email;
    }

    private function getPharmaPlanUser(int $id): ?int
    {
        return (new PharmaPlanUser())
            ->find()
            ->where(['email' => $this->getUserEmail($id)])
            ->first()
            ?->id;
    }
}
