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
    foreach ($pathFinder->generatePath(new Path($start), /*11*/ 8) as $path) {
        $word = $board->getWord($path);
        if ($globalDictionary->contain($word)) {
            $words[] = $word;
        }
    }

    return $words;
};

/** @var Future[] $futures */
$futures = [];
echo 'Running new runtime... ';
for ($y = 0; $y < $board->size; ++$y) {
    for ($x = 0; $x < $board->size; ++$x) {
        $futures[] = $future = run($findValidWords, [new Position($x, $y)]);
        echo count($futures);
    }
}
echo PHP_EOL;
/** @var Word[] $validWords */
$validWords = [];
echo 'Waiting future result... ';
foreach ($futures as $key => $future) {
    echo $key + 1;
    $validWords = [...$validWords, ...$future->value()];
}
echo PHP_EOL;

usort($validWords, fn($a, $b): int => $b->point <=> $a->point);
for ($i = 0; $i < 10; ++$i) {
    $word = $validWords[$i];
    echo "#$i: $word->chars ($word->point) ";
    foreach($word->path as $p) echo "($p->x,$p->y)";
    echo PHP_EOL;
}