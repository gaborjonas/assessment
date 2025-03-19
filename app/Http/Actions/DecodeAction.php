<?php

declare(strict_types=1);

namespace App\Http\Actions;

use App\Http\Requests\DecodeRequest;
use App\UrlShortener\CachedUrlShortener;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class DecodeAction
{
    public function __invoke(DecodeRequest $request, CachedUrlShortener $urlShortener): JsonResponse
    {
        $shortUrl = $request->string('url')->trim()->toString();

        $originalUrl = $urlShortener->retrieve($shortUrl);

        abort_if($originalUrl === null, JsonResponse::HTTP_NOT_FOUND, 'URL not found');

        return new JsonResponse([
            'original_url' => $originalUrl,
            'short_url' => $shortUrl,
        ]);
    }
}
