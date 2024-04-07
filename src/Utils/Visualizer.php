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
                $letter = $board->getLetter($position = new Position($x, $y));
                $letters[] = match (true) {
                    $letter->multiply === 2 => "\e[0;93m$letter->char\e[0m",
                    $letter->multiply === 3 => "\e[0;33m$letter->char\e[0m",
                    $board->doubleWord == $position => "\e[0;105m$letter->char\e[0m",
                    default => $letter->char,
                };
            }
        }

        $lines = array_map(fn($line) => implode(" ", $line), array_chunk($letters, $board->size));
        return implode(PHP_EOL, $lines);
    }
}