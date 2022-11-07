<?php

declare(strict_types=1);

use Nerahikada\SpellForecast\Algorithm\PathFinder;
use Nerahikada\SpellForecast\BlackBox\WordDictionary;
use Nerahikada\SpellForecast\Human\BoardParser;
use Nerahikada\SpellForecast\Path;
use Nerahikada\SpellForecast\Position;
use Nerahikada\SpellForecast\Word;
use parallel\Future;

use function parallel\bootstrap;
use function parallel\run;

require 'vendor/autoload.php';
bootstrap('vendor/autoload.php');

$globalDictionary = new WordDictionary();

$board = (new BoardParser())->result();

/** @return Word[] */
$findValidWords = function (/*Position */ $start) use ($globalDictionary, $board): array {
    $words = [];

    $pathFinder = new PathFinder($board);
    foreach ($pathFinder->generatePath(new Path($start), /*11*/ 6) as $path) {
        $word = $board->getWord($path);
        if ($globalDictionary->contain($word)) {
            $words[] = $word;
        }
    }

    return $words;
};

/** @var Word[] $validWords */
$validWords = [];

/** @var Future[] $futures */
//$futures = [];
for ($y = 0; $y < $board->size; ++$y) {
    for ($x = 0; $x < $board->size; ++$x) {
        //$futures[] = run($findValidWords, [new Position($x, $y)]);
        $validWords = [...$validWords, ...$findValidWords(new Position($x, $y))];
    }
}
/** @var Word[] $validWords */
/*
$validWords = [];
foreach ($futures as $future) {
    $validWords = [...$validWords, ...$future->value()];
}
*/

usort($validWords, fn($a, $b): int => $b->point <=> $a->point);
for ($i = 0; $i < 5; ++$i) {
    echo '#' . ($i + 1) . ': ' . $validWords[$i]->chars . ' (' . $validWords[$i]->point . ')' . PHP_EOL;
}