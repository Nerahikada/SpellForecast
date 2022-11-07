<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast;

use InvalidArgumentException;
use OutOfRangeException;

final class Board
{
    public readonly int $size;
    private readonly array $letters;
    private readonly ?Position $doubleWord;

    /**
     * @param Letter[] $letters
     */
    public function __construct(array $letters, ?int $doubleWord = null)
    {
        array_map(fn($object) => assert($object instanceof Letter), $letters);
        $this->letters = array_values($letters);

        $this->size = (int)sqrt($count = count($this->letters));
        if ($this->size < 2) {
            throw new InvalidArgumentException('The board size must be 2 or larger');
        }
        if ($this->size ** 2 !== $count) {
            throw new InvalidArgumentException('The number of letters must be a square number');
        }

        if ($doubleWord !== null) {
            $doubleWord = new Position($doubleWord % $this->size, (int)($doubleWord / $this->size));
            $this->validatePosition($doubleWord);
        }
        $this->doubleWord = $doubleWord;
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
        if ($this->doubleWord !== null && $path->has($this->doubleWord)) {
            $point *= 2;
        }
        return new Word($path, implode("", $letters), $point);
    }
}