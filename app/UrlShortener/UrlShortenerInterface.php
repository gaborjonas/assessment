<?php

declare(strict_types=1);

namespace App\UrlShortener;

interface UrlShortenerInterface
{
    public function shorten(string $url): string;
}
