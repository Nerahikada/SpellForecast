<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Nerahikada\SpellForecast\Board;
use Nerahikada\SpellForecast\Letter;
use Nerahikada\SpellForecast\Position;

$board = new Board([
    new Letter('D'), new Letter('R'), new Letter('M'), new Letter('O'), new Letter('E'),
    new Letter('N'), new Letter('O'), new Letter('N'), new Letter('S'), new Letter('K'),
    new Letter('A'), new Letter('A'), new Letter('T'), new Letter('A'), new Letter('T'),
    new Letter('O'), new Letter('R'), new Letter('W'), new Letter('F'), new Letter('I'),
    new Letter('A'), new Letter('Y', 2), new Letter('O'), new Letter('N'), new Letter('O'),
], new Position(4, 3));

for ($y = 0; $y < 5; ++$y) {
    for ($x = 0; $x < 5; ++$x) {
        echo $board->getLetter(new Position($x, $y)) . ' ';
    }
    echo PHP_EOL;
}