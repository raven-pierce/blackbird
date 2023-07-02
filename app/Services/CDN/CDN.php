<?php

namespace App\Services\CDN;

interface CDN
{
    public function purge(string $filePath): void;
}
