<?php

namespace App\Services\CDN;

use Illuminate\Support\Facades\Http;

class DigitalOceanCDN implements CDN
{
    public function purge(string $filePath): void
    {
        $endpoint = config('filesystems.disks.do.cdn_endpoint').'/cache';

        Http::asJson()->delete($endpoint,
            [
                'files' => [$filePath],
            ],
        );
    }
}
