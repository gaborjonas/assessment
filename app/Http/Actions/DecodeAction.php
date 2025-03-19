<?php

declare(strict_types=1);

namespace App\Http\Actions;

use App\Http\Requests\DecodeRequest;
use App\UrlShortener\UrlShortener;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class DecodeAction
{
    public function __invoke(DecodeRequest $request, UrlShortener $urlShortener): JsonResponse
    {
        $url = (string) $request->string('url')->trim();

        $originalUrl = $urlShortener->retrieve($url);

        abort_if($originalUrl === null, 404, 'URL not found');

        return new JsonResponse([
            'url' => $originalUrl,
            'short_url' => $url,
        ]);
    }
}
