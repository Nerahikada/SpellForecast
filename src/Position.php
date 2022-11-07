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
            throw new InvalidArgumentException('X must be a positive integer');
        }
        if ($y < 0) {
            throw new InvalidArgumentException('Y must be a positive integer');
        }

        /**
         * @link https://stackoverflow.com/a/682617
         */
        $this->hash = ($x + $y) * ($x + $y + 1) / 2 + $y;
    }

    public function add(int $x, int $y): self
    {
        return new self($this->x + $x, $this->y + $y);
    }

    public function subtract(int $x, int $y): self
    {
        return new self($this->x - $x, $this->y - $y);
    }

    public function distance(Position $position): int
    {
        return max(abs($this->x - $position->x), abs($this->y - $position->y));
    }

    /**
     * @return Position[]
     */
    public function around(int $limit = 4): array
    {
        $positions = [];

        for ($y = $this->y - 1; $y <= $this->y + 1; ++$y) {
            for ($x = $this->x - 1; $x <= $this->x + 1; ++$x) {
                if ($x >= 0 && $x <= $limit && $y >= 0 && $y <= $limit) {
                    $positions[] = new Position($x, $y);
                }
            }
        }

        return $positions;
    }
}