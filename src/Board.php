<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast;

use Generator;
use InvalidArgumentException;
use OutOfRangeException;

final class Board
{
    public readonly int $size;
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

        $this->validatePosition($doubleWord);
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

    public function getWord(Path $path): Word
    {
        $letters = array_map($this->getLetter(...), iterator_to_array($path));
        $point = array_sum(array_map(fn(Letter $l) => $l->point, $letters));
        if ($path->has($this->doubleWord)) {
            $point *= 2;
        }
        return new Word($path, implode("", $letters), $point);
    }
}