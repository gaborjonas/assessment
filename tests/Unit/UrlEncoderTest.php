<?php

declare(strict_types=1);

use App\UrlShortener\UrlEncoder;
use App\UrlShortener\UrlEncoderInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class UrlEncoderTest extends TestCase {

    private function createUrlEncoder(string $scheme, string $domain, int $pathLength): UrlEncoderInterface
    {
        return new UrlEncoder($scheme, $domain, $pathLength);
    }


    #[Test]
    public function encode_returns_unique_full_url(): void
    {
        $encoder = $this->createUrlEncoder('https', 'short.sh', 10);

        $shortFirst = $encoder->encode('https://www.thisisalongdomain.com/with/some/parameters?and=here_too');
        $shortSecond = $encoder->encode('https://www.thisisalongdomain.com/with/some/parameters?and=here_too');

        $this->assertStringStartsWith('https://short.sh/', $shortFirst);
        $this->assertStringStartsWith('https://short.sh/', $shortSecond);

        $this->assertSame($shortFirst, $shortSecond);
    }
}
