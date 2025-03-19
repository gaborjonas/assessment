<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\CannotStoreUrlException;
use App\UrlShortener\UrlEncoderInterface;
use App\UrlShortener\UrlShortener;
use App\UrlShortener\UrlShortenerInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Cache\Repository as CacheInterface;

final class UrlShortenerTest extends TestCase {

    private UrlEncoderInterface&MockObject $urlEncoder;
    private CacheInterface&MockObject $cacheManager;

    protected function setUp(): void
    {
        $this->urlEncoder = $this->createMock(UrlEncoderInterface::class);
        $this->cacheManager = $this->createMock(CacheInterface::class);
    }

    private function createUrlShortener(): UrlShortenerInterface
    {
        return new UrlShortener(
            $this->urlEncoder,
            $this->cacheManager,
        );
    }

    #[Test]
    public function shorten_returns_from_cache_if_set(): void
    {
        $shortUrl = 'short.url';
        $longUrl = 'long.url';

        $this->urlEncoder
            ->expects($this->once())
            ->method('encode')
            ->with($longUrl)
            ->willReturn($shortUrl);

        $this->cacheManager
            ->expects($this->once())
            ->method('get')
            ->with($shortUrl)
            ->willReturn($shortUrl);

        $urlShortener = $this->createUrlShortener();

        $urlShortener->shorten('long.url');
    }

    #[Test]
    public function shorten_caches_and_returns_short_url_if_not_set(): void
    {
        $expectedShortUrl = 'short.url';
        $longUrl = 'long.url';

        $this->urlEncoder
            ->expects($this->once())
            ->method('encode')
            ->with($longUrl)
            ->willReturn($expectedShortUrl);

        $this->cacheManager
            ->expects($this->once())
            ->method('get')
            ->with($expectedShortUrl)
            ->willReturn(null);

        $this->cacheManager
            ->expects($this->once())
            ->method('put')
            ->with($expectedShortUrl)
            ->willReturn(true);

        $urlShortener = $this->createUrlShortener();

        $shortUrl = $urlShortener->shorten('long.url');

        $this->assertSame($expectedShortUrl, $shortUrl);
    }

    #[Test]
    public function shorten_throws_exception_if_put_fails(): void
    {
        $expectedShortUrl = 'short.url';
        $longUrl = 'long.url';

        $this->urlEncoder
            ->expects($this->once())
            ->method('encode')
            ->with($longUrl)
            ->willReturn($expectedShortUrl);

        $this->cacheManager
            ->expects($this->once())
            ->method('get')
            ->with($expectedShortUrl)
            ->willReturn(null);

        $this->cacheManager
            ->expects($this->once())
            ->method('put')
            ->with($expectedShortUrl)
            ->willReturn(false);

        $urlShortener = $this->createUrlShortener();

        $this->expectException(CannotStoreUrlException::class);
        $urlShortener->shorten('long.url');
    }

    #[Test]
    public function retrieve_returns_null_if_not_set(): void
    {
        $shortUrl = 'short.url';

        $this->cacheManager
            ->expects($this->once())
            ->method('get')
            ->with($shortUrl)
            ->willReturn(null);

        $urlShortener = $this->createUrlShortener();

        $longUrl = $urlShortener->retrieve($shortUrl);

        $this->assertNull($longUrl);
    }

    #[Test]
    public function retrieve_returns_url_if_set(): void
    {
        $shortUrl = 'short.url';
        $expectedLongUrl = 'long.url';

        $this->cacheManager
            ->expects($this->once())
            ->method('get')
            ->with($shortUrl)
            ->willReturn($expectedLongUrl);

        $urlShortener = $this->createUrlShortener();

        $longUrl = $urlShortener->retrieve($shortUrl);

        $this->assertSame($expectedLongUrl, $longUrl);
    }
}
