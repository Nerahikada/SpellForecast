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

$board = (new BoardParser())->result();

/** @return string[] */
$findValidWords = function (string $serializedBoard, string $serializedPosition) : array {
    $board = unserialize($serializedBoard);
    $start = unserialize($serializedPosition);

    $pathFinder = new PathFinder($board);
    $dictionary = new WordDictionary();
    $serializedWords = [];

    foreach ($pathFinder->generatePath(new Path($start), /*11*/ 8) as $path) {
        $word = $board->getWord($path);
        if ($dictionary->contain($word)) {
            $serializedWords[] = serialize($word);
        }
    }

    return $serializedWords;
};

/** @var Future[] $futures */
$futures = [];
echo 'Running new runtime... ';
for ($y = 0; $y < $board->size; ++$y) {
    for ($x = 0; $x < $board->size; ++$x) {
        $futures[] = run($findValidWords, [serialize($board), serialize(new Position($x, $y))]);
        echo count($futures);
    }
}
echo PHP_EOL;

/** @var Word[] $validWords */
$validWords = [];
echo 'Waiting future result... ';
foreach ($futures as $key => $future) {
    echo $key + 1;
    array_push($validWords, ...array_map(unserialize(...), $future->value()));
}
echo PHP_EOL;

usort($validWords, fn($a, $b): int => $b->point <=> $a->point);

for ($i = 0; $i < 5; ++$i) {
    $word = $validWords[$i];
    echo str_pad('#' . ($i + 1), 3, pad_type: STR_PAD_LEFT);
    echo ": $word->chars ($word->point) ";
    foreach($word->path as $p) echo "($p->x,$p->y)";
    echo PHP_EOL;
}