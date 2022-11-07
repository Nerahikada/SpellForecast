<?php

declare(strict_types=1);

use Nerahikada\SpellForecast\Algorithm\PathFinder;
use Nerahikada\SpellForecast\BlackBox\WordDictionary;
use Nerahikada\SpellForecast\Board;
use Nerahikada\SpellForecast\Letter;
use Nerahikada\SpellForecast\Path;
use Nerahikada\SpellForecast\Position;
use Nerahikada\SpellForecast\Word;
use parallel\Future;

use function parallel\bootstrap;
use function parallel\run;

require 'vendor/autoload.php';
bootstrap('vendor/autoload.php');

$globalDictionary = new WordDictionary();

// FIX: 入力を全面信頼したゴミ
$letters = [];
$inputs = readline('Input board: ');
$offset = 0;
while(strlen($inputs) > $offset){
    $char = $inputs[$offset];
    $multiply = 1;
    if(ctype_digit($inputs[$offset + 1] ?? '')){
        $multiply = (int) $inputs[++$offset];
    }
    $letters[] = new Letter($char, $multiply);
    ++$offset;
}
$doubleWord = array_values(array_filter(str_split(readline('Input double word (XY): ')), ctype_alnum(...)));
$doubleWord = !empty($doubleWord) ? new Position((int) $doubleWord[0], (int) $doubleWord[1]) : null;

$board = new Board($letters, $doubleWord);

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