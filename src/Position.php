<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast;

use InvalidArgumentException;

final class Position
{
    public readonly int $hash;

    public function __construct(public readonly int $x, public readonly int $y)
    {
        if ($x < 0) {
            throw new InvalidArgumentException('X must be a natural number');
        }
        if ($y < 0) {
            throw new InvalidArgumentException('Y must be a natural number');
        }

        /**
         * @link https://stackoverflow.com/a/682617
         */
        $this->hash = ($x + $y) * ($x + $y + 1) / 2 + $y;
    }
}