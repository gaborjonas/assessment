<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\UrlShortener\UrlShortenerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class DecodeActionTest extends TestCase
{
    #[Test]
    public function invalid_method_returns_not_allowed(): void
    {
        $response = $this->getJson('/api/decode');

        $response->assertMethodNotAllowed();
    }

    #[Test]
    #[DataProvider('validation_test_data_provider')]
    public function returns_validation_errors(string $input, array $expectedError): void
    {
        $response = $this->postJson('/api/decode', ['url' => $input]);

        $response
            ->assertUnprocessable()
            ->assertExactJson($expectedError);
    }

    public static function validation_test_data_provider(): array
    {
        return [
            'no url' => [
                'input' => '',
                'expectedError' => [
                    'message' => 'The url field is required.',
                    'errors' => [
                        'url' => [
                            'The url field is required.',
                        ],
                    ],
                ],
            ],
            'invalid url' => [
                'input' => 'invalid-url',
                'expectedError' => [
                    'message' => 'The url field must be a valid URL.',
                    'errors' => [
                        'url' => [
                            'The url field must be a valid URL.',
                        ],
                    ],
                ],
            ],
        ];
    }

    #[Test]
    public function returns_not_found_if_not_found(): void
    {
        $response = $this->postJson('/api/decode', ['url' => 'https://short.url']);

        $response
            ->assertNotFound()
            ->assertJson(['message' => 'URL not found']);
    }


    #[Test]
    public function returns_the_short_and_the_original_url_if_found(): void
    {
        $longUrl = 'https://www.thisisalongdomain.com/with/some/parameters?and=here_too';
        $shortUrl = $this->createShortUrl($longUrl);

        $response = $this->postJson('/api/decode', ['url' => $shortUrl]);

        $response
            ->assertOk()
            ->assertExactJson([
                'short_url' => $shortUrl,
                'url' => $longUrl,
            ]);
    }

    private function createShortUrl(string $originalUrl): string
    {
        $urlEncoder = $this->app->make(UrlShortenerInterface::class);
        return $urlEncoder->shorten($originalUrl);
    }

}
