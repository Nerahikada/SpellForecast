<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Utils;

use Nerahikada\SpellForecast\Board;
use Nerahikada\SpellForecast\Position;

final class Visualizer
{
    public static function board(Board $board): string
    {
        /** @var string[] $letters */
        $letters = [];

        for ($y = 0; $y < $board->size; ++$y) {
            for ($x = 0; $x < $board->size; ++$x) {
                // TODO: colorize
                $letters[] = (string)$board->getLetter(new Position($x, $y));
            }
        }

        $lines = array_map(fn($line) => implode(" ", $line), array_chunk($letters, $board->size));
        return implode(PHP_EOL, $lines);
    }
}