<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Algorithm;

use Generator;
use Nerahikada\SpellForecast\Board;
use Nerahikada\SpellForecast\Path;

final class PathFinder
{
    public function __construct(private readonly Board $board)
    {
    }

    /**
     * @yield Path
     */
    public function generatePath(Path $root, int $depth = 1, int &$current = 0): Generator
    {
        foreach ($root->latest()->around($this->board->size - 1) as $position) {
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
}