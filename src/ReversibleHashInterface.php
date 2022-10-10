<?php

declare(strict_types = 1);

namespace AvtoDev\HashGeneratorLaravel;

interface ReversibleHashInterface
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function generate(string $value): string;

    /**
     * @param string $hash
     *
     * @return string
     */
    public function regenerate(string $hash): string;
}
