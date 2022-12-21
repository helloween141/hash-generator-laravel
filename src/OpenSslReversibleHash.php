<?php

declare(strict_types = 1);

namespace AvtoDev\HashGeneratorLaravel;



use AvtoDev\HashGeneratorLaravel\Exceptions\DoNotDecryptDataException;
use AvtoDev\HashGeneratorLaravel\Exceptions\DoNotEncryptDataException;
use AvtoDev\HashGeneratorLaravel\Exceptions\HashNormalizationException;

class OpenSslReversibleHash implements ReversibleHashInterface
{
    /**
     * @var string
     *
     * @link https://www.php.net/manual/ru/function.openssl-get-cipher-methods.php
     */
    private string $cipher_algo;

    /**
     * @var string
     *
     * @link https://www.php.net/manual/ru/function.openssl-random-pseudo-bytes.php
     */
    private string $passphrase;

    /**
     * @var int
     */
    private int $options;

    /**
     * @param string $cipher_algo
     * @param string $passphrase
     * @param int    $options
     */
    public function __construct(string $cipher_algo, string $passphrase, int $options)
    {
        $this->cipher_algo = $cipher_algo;
        $this->passphrase  = $passphrase;
        $this->options     = $options;
    }

    /**
     * {@inheritDoc}
     *
     * @throws DoNotEncryptDataException
     */
    public function generate(string $value): string
    {
        return \base64_encode($this->encrypt($value));
    }

    /**
     * {@inheritDoc}
     *
     * @throws HashNormalizationException
     * @throws DoNotDecryptDataException
     */
    public function regenerate(string $hash): string
    {
        $normalized_hash = \base64_decode($hash, true);

        if (\is_bool($normalized_hash)) {
            throw new HashNormalizationException(\sprintf('Hash "%s" do not normalized', $hash));
        }

        return $this->decrypt($normalized_hash);
    }

    /**
     * @param string $data
     *
     * @throws DoNotEncryptDataException
     *
     * @return string
     *
     * @link https://www.php.net/manual/ru/function.openssl-encrypt.php
     */
    protected function encrypt(string $data): string
    {
        $encrypted_data = \openssl_encrypt(
            $data,
            $this->cipher_algo,
            $this->passphrase,
            $this->options,
            $this->getInitVector()
        );

        if (\is_bool($encrypted_data)) {
            throw new DoNotEncryptDataException();
        }

        return $encrypted_data;
    }

    /**
     * @param string $code
     *
     * @throws DoNotDecryptDataException
     *
     * @return string
     *
     * @link https://www.php.net/manual/ru/function.openssl-decrypt.php
     */
    protected function decrypt(string $code): string
    {
        $decrypted_data = \openssl_decrypt(
            $code,
            $this->cipher_algo,
            $this->passphrase,
            $this->options,
            $this->getInitVector()
        );

        if (\is_bool($decrypted_data)) {
            throw new DoNotDecryptDataException();
        }

        return $decrypted_data;
    }

    /**
     * @return string
     */
    protected function getInitVector(): string
    {
        return \md5($this->passphrase, true);
    }
}
