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
        $url = (string) $request->string('url')->trim();

        return new JsonResponse([
            'original_url' => $url,
            'short_url' => $urlShortener->shorten($url),
        ]);
    }
}
