<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast;

final class Word
{
    public readonly int $point;

    public function __construct(
        public readonly Path $path,
        public readonly string $chars,
        int $point
    ) {
        if (strlen($chars) >= 7) {
            $point += 20;
        }
        $this->point = $point;
    }

    public function __toString(): string
    {
        return $this->chars;
    }
}