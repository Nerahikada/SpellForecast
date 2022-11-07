<?php

declare(strict_types=1);

use Nerahikada\SpellForecast\Algorithm\PathFinder;
use Nerahikada\SpellForecast\BlackBox\WordDictionary;
use Nerahikada\SpellForecast\Board;
use Nerahikada\SpellForecast\Letter;
use Nerahikada\SpellForecast\Path;
use Nerahikada\SpellForecast\Position;
use parallel\Future;

use function parallel\bootstrap;
use function parallel\run;

require 'vendor/autoload.php';
bootstrap('vendor/autoload.php');

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

$pathFinder = new PathFinder($board);
$globalDictionary = new WordDictionary();

/** @var Future[] $futures */
$futures = [];
for ($y = 0; $y < $board->size; ++$y) {
    for ($x = 0; $x < $board->size; ++$x) {
        $futures[] = run(function () use ($globalDictionary, $pathFinder, $x, $y) {
            $words = [];
            foreach ($pathFinder->generatePath(new Path(new Position($x, $y)), /*11*/7) as $path) {
                $word = $pathFinder->board->getWord($path);
                if ($globalDictionary->contain($word)) {
                    $words[] = $word;
                }
            }
            return $words;
        });
    }
}

$validWords = [];
foreach ($futures as $future) {
    $validWords = [...$validWords, ...$future->value()];
}

usort($validWords, fn($a, $b) : int => $b->point <=> $a->point);
for($i = 0; $i < 5; ++$i){var_dump(current($validWords)->point);next($validWords);}