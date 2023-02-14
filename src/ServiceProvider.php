<?php

declare(strict_types = 1);

namespace AvtoDev\HashGeneratorLaravel;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        $this->app->bind(ReversibleHashInterface::class, static function (Container $app): ReversibleHashInterface {
            /** @var Repository $config */
            $config = $app->make(Repository::class);

            /** @var string $cipher_algo */
            $cipher_algo = $config->get('hash-generator.cipher_algo', 'aes-128-cfb');

            /** @var string $passphrase */
            $passphrase = $config->get('hash-generator.passphrase', 'secret');

            /** @var int $options */
            $options = $config->get('hash-generator.options', 0);

            return new OpenSslReversibleHash($cipher_algo, $passphrase, $options);
        });
    }
}
