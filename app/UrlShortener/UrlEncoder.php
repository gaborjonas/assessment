<?php

declare(strict_types=1);

namespace App\UrlShortener;

final readonly class UrlEncoder implements UrlEncoderInterface
{
    public function __construct(
        private string $scheme,
        private string $domain,
        private int $pathLength,
    ) {}

    public function encode(string $url): string
    {
        return sprintf('%s://%s/%s', $this->scheme, $this->domain, $this->createPath($url));
    }

    private function createPath(string $path): string
    {
        return substr(
            base64_encode(sha1($path)),
            0,
            $this->pathLength,
        );
    }
}
