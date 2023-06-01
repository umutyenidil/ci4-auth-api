<?php

use Ramsey\Uuid\Uuid;

if (!function_exists('generateUUID')) {

    function generateUUID(): string
    {
        $generatedUuid = Uuid::uuid4();

        return $generatedUuid->toString();
    }


}