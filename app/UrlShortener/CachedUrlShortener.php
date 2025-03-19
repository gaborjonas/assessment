<?php

declare(strict_types=1);

namespace App\UrlShortener;

use App\Exceptions\CannotStoreUrlException;
use Illuminate\Contracts\Cache\Repository as CacheInterface;

final readonly class CachedUrlShortener implements UrlShortenerInterface
{
    public function __construct(
        private UrlEncoderInterface $urlEncoder,
        private CacheInterface $cacheManager,
    ) {}

    public function shorten(string $url): string
    {
        $shortUrl = $this->urlEncoder->encode($url);

        $short = $this->cacheManager->get($shortUrl);

        if ($short !== null) {
            return $shortUrl;
        }

        $isStored = $this->cacheManager->put($shortUrl, $url);

        if ($isStored === false) {
            throw new CannotStoreUrlException();
        }

        return $shortUrl;
    }

    public function retrieve(string $url): ?string
    {
        $cachedUrl = $this->cacheManager->get($url);

        if ($cachedUrl === null) {
            return null;
        }

        return (string) $cachedUrl;
    }
}
