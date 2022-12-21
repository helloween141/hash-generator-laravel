<?php

declare(strict_types = 1);

namespace AvtoDev\HashGeneratorLaravel\Tests;

use AvtoDev\HashGeneratorLaravel\Exceptions\HashNormalizationException;
use AvtoDev\HashGeneratorLaravel\OpenSslReversibleHash;

/**
 * @group  services
 *
 * @covers \AvtoDev\HashGeneratorLaravel\OpenSslReversibleHash
 */
class OpenSslReversibleHashTest extends AbstractTestCase
{
    public function testGenerate(): void
    {
        $ssl_hash = new OpenSslReversibleHash(
            'aes-128-cfb',
            'salt',
            \OPENSSL_ZERO_PADDING
        );

        $this->assertEquals('bVFxWmpkekNhS09BTEI0PQ==', $ssl_hash->generate('secret data'));
    }

    public function testRegenerate(): void
    {
        $ssl_hash = new OpenSslReversibleHash(
            'aes-128-cfb',
            'salt',
            \OPENSSL_ZERO_PADDING
        );

        $this->assertEquals('secret data', $ssl_hash->regenerate('bVFxWmpkekNhS09BTEI0PQ=='));
    }

    public function testRegenerateNormalizationFail(): void
    {
        $this->expectException(HashNormalizationException::class);
        $this->expectExceptionMessage('Hash "#iu3498r" do not normalized');

        $ssl_hash = new OpenSslReversibleHash(
            'aes-128-cfb',
            'salt',
            \OPENSSL_ZERO_PADDING
        );

        $ssl_hash->regenerate('#iu3498r');
    }
}
