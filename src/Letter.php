<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast;

use InvalidArgumentException;

final class Letter
{
    public readonly string $char;
    public readonly int $point;

    public function __construct(string $char, public readonly int $multiply = 1)
    {
        if (strlen($char) !== 1) {
            throw new InvalidArgumentException('Char must be a single character');
        }

        $char = strtoupper($char);
        if (preg_match('/[A-Z]/', $char) !== 1) {
            throw new InvalidArgumentException('Char must be a letter from A to Z');
        }

        $this->char = $char;

        $this->point = $multiply * match ($this->char) {
                "A", "E", "I", "O" => 1,
                "N", "R", "S", "T" => 2,
                "D", "G", "L" => 3,
                "B", "H", "M", "P", "U", "Y" => 4,
                "C", "F", "V", "W" => 5,
                "K" => 6,
                "J", "X" => 7,
                "Q", "Z" => 8,
            };
    }

    public function __toString(): string
    {
        return $this->char;
    }
}