<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast;

use InvalidArgumentException;
use Iterator;

final class Path implements Iterator
{
    /** @var Position[] */
    private array $nodes;

    public function __construct(Position $start)
    {
        $this->nodes[$start->hash] = $start;
    }

    public function append(Position $position): self
    {
        if ($this->has($position)) {
            throw new InvalidArgumentException('Do not pass the same coordinates twice');
        }
        if ($this->valid() && $this->latest()->distance($position) > 1) {
            throw new InvalidArgumentException('The distance must be 1');
        }

        $path = clone $this;
        $path->nodes[$position->hash] = $position;

        return $path;
    }

    public function has(Position $position): bool
    {
        return isset($this->nodes[$position->hash]);
    }

    public function latest(): Position
    {
        return $this->nodes[array_key_last($this->nodes)];
    }

    public function valid(): bool
    {
        return current($this->nodes) !== false;
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

    public function rewind(): void
    {
        reset($this->nodes);
    }
}