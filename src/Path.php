<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast;

use Countable;
use InvalidArgumentException;
use Iterator;

final class Path implements Iterator, Countable
{
    /** @var Position[] */
    private array $nodes;

    public function __construct(Position $start)
    {
        $this->nodes[$start->hash] = $start;
    }

    /**
     * 要素を追加し、ポインタを次に進めます。
     */
    public function append(Position $position): void
    {
        if (isset($this->nodes[$position->hash])) {
            throw new InvalidArgumentException('Do not pass the same coordinates twice');
        }
        if ($this->valid() && $this->current()->distance($position) > 1) {
            throw new InvalidArgumentException('The distance must be 1');
        }
        $this->nodes[$position->hash] = $position;
        $this->next();
    }

    public function current(): Position
    {
        return current($this->nodes);
    }

    public function next(): void
    {
        next($this->nodes);
    }

    public function key(): int
    {
        return key($this->nodes);
    }

    public function valid(): bool
    {
        return current($this->nodes) !== false;
    }

    public function rewind(): void
    {
        reset($this->nodes);
    }

    public function count(): int
    {
        return count($this->nodes);
    }
}