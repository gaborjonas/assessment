<?php

declare(strict_types=1);

namespace App\UrlShortener;

interface UrlEncoderInterface
{
    public function encode(string $url): string;
}
