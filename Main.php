<?php

declare(strict_types=1);

use Nerahikada\SpellForecast\Algorithm\PathFinder;
use Nerahikada\SpellForecast\BlackBox\WordDictionary;
use Nerahikada\SpellForecast\Board;
use Nerahikada\SpellForecast\Human\BoardParser;
use Nerahikada\SpellForecast\Path;
use Nerahikada\SpellForecast\Position;
use Nerahikada\SpellForecast\Word;

require 'vendor/autoload.php';

$board = (new BoardParser())->result();

$dictionary = new WordDictionary();

/** @return Word[] */
$findValidWords = function (Board $board, Position $start) use ($dictionary): array {
    $pathFinder = new PathFinder($board);
    $words = [];

    foreach ($pathFinder->generatePath(new Path($start), /*11*/ 8) as $path) {
        $word = $board->getWord($path);
        if ($dictionary->contain($word)) {
            $words[] = $word;
        }
    }

    return $words;
};

/** @var Word[] $validWords */
$validWords = [];
echo 'Finding words...' . PHP_EOL;
for ($y = 0; $y < $board->size; ++$y) {
    for ($x = 0; $x < $board->size; ++$x) {
        array_push($validWords, ...$findValidWords($board, new Position($x, $y)));
    }
}

usort($validWords, fn($a, $b): int => $b->point <=> $a->point);

for ($i = 0; $i < 5; ++$i) {
    $word = $validWords[$i];
    echo str_pad('#' . ($i + 1), 3, pad_type: STR_PAD_LEFT);
    echo ": $word->chars ($word->point) ";
    foreach($word->path as $p) echo "($p->x,$p->y)";
    echo PHP_EOL;
}