<?php

declare(strict_types=1);

namespace App\Http\Actions;

use App\Http\Requests\EncodeRequest;
use App\UrlShortener\UrlShortenerInterface;
use Illuminate\Http\JsonResponse;

final readonly class EncodeAction
{
    public function __invoke(EncodeRequest $request, UrlShortenerInterface $urlShortener): JsonResponse
    {
        $originalUrl = $request->string('url')->trim()->toString();

        return new JsonResponse([
            'original_url' => $originalUrl,
            'short_url' => $urlShortener->shorten($originalUrl),
        ]);
    }
}
