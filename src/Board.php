<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast;

use InvalidArgumentException;
use OutOfRangeException;

final class Board
{
    private readonly int $size;
    private readonly array $letters;

    /**
     * @param Letter[] $letters
     */
    public function __construct(array $letters, private readonly Position $doubleWord)
    {
        array_map(fn($object) => assert($object instanceof Letter), $letters);
        $this->letters = array_values($letters);

        $size = (int)sqrt($count = count($this->letters));
        if ($size ** 2 !== $count) {
            throw new InvalidArgumentException('The number of letters must be a square number');
        }

        $this->size = $size;

        $this->validatePosition($this->doubleWord);
    }

    private function validatePosition(Position $position): void
    {
        if ($position->x > $this->size - 1) {
            throw new OutOfRangeException('X must be less than ' . $this->size);
        }
        if ($position->y > $this->size - 1) {
            throw new OutOfRangeException('Y must be less than ' . $this->size);
        }
    }

    public function getLetter(Position $position): Letter
    {
        $this->validatePosition($position);
        return $this->letters[$position->x + $position->y * $this->size];
    }
}