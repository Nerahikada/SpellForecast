<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast;

final class Word
{
    public function __construct(
        public readonly Path $path,
        public readonly string $chars,
        public readonly int $point
    ) {
    }
}