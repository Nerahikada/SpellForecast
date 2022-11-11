<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Algorithm;

use Generator;
use Nerahikada\SpellForecast\Path;
use Nerahikada\SpellForecast\Position;

final class PathFinder
{
    public function __construct(private readonly int $boardSize)
    {
    }

    /**
     * @yield Path
     */
    public function generatePath(Path $root, int $depth = 1, int &$current = 0): Generator
    {
        foreach ($root->latest()->around($this->boardSize - 1) as $position) {
            if (!$root->has($position)) {
                $path = $root->append($position);
                if (++$current < $depth) {
                    yield from $this->generatePath($path, $depth, $current);
                }
                --$current;
                yield $path;
            }
        }
    }

    /**
     * @return Position[]
     */
    public function boardPositions(): array
    {
        return array_map(
            fn(int $n) => new Position($n % $this->boardSize, (int)($n / $this->boardSize)),
            range(0, $this->boardSize ** 2 - 1)
        );
    }
}