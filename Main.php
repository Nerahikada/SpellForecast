<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Nerahikada\SpellForecast\Board;
use Nerahikada\SpellForecast\Letter;
use Nerahikada\SpellForecast\Path;
use Nerahikada\SpellForecast\Position;

$board = new Board([
    new Letter('D'), new Letter('R'), new Letter('M'), new Letter('O'), new Letter('E'),
    new Letter('N'), new Letter('O'), new Letter('N'), new Letter('S'), new Letter('K'),
    new Letter('A'), new Letter('A'), new Letter('T'), new Letter('A'), new Letter('T'),
    new Letter('O'), new Letter('R'), new Letter('W'), new Letter('F'), new Letter('I'),
    new Letter('A'), new Letter('Y', 2), new Letter('O'), new Letter('N'), new Letter('O'),
], new Position(4, 3));

for ($y = 0; $y < $board->size; ++$y) {
    for ($x = 0; $x < $board->size; ++$x) {
        echo $board->getLetter(new Position($x, $y)) . ' ';
    }
    echo PHP_EOL;
}

/** @yield Path */
function generatePath(Board $board, Path $root, int $depth = 1, int &$current = 0): Generator
{
    foreach($root->latest()->around($board->size - 1) as $position){
        if(!$root->has($position)){
            $path = $root->append($position);
            if(++$current < $depth){
                yield from generatePath($board, $path, $depth, $current);
            }
            --$current;
            yield $path;
        }
    }
}

$pathCounts = [];
foreach(generatePath($board, new Path(new Position(2, 2)), 9) as $path){
    $c = $path->count();
    if(!isset($pathCounts[$c])) $pathCounts[$c] = 0;
    ++$pathCounts[$c];
}
echo "Start from (2, 2):\n";
foreach($pathCounts as $length => $count){
    echo str_pad((string) $length, 2, pad_type: STR_PAD_LEFT) . " Letters: $count Paths\n";
}