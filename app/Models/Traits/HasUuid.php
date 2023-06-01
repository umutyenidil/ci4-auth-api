<?php

namespace App\Models\Traits;

trait HasUuid
{
    public function setNewUUID(array $data): array
    {
        if (empty($data['data']['uuid'])) {
            $data['data']['uuid'] = generateUUID();
        }

        return $data;
    }
}

?>