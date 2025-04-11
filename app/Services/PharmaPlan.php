<?php

namespace App\Services;

class PharmaPlan
{
    public static function createTicket(array $data, array $files = []): int
    {
        try {
            $id = (new PharmaPlanTicket)->create(array_merge($data, $files));

            (new PharmaPlanMedia)->create($id, $files);

            return $id;
        } catch (\Exception $e) {
        }
    }

    public static function createComment(array $data): void
    {
        (new PharmaPlanTicketComment)->create($data);
    }
}
