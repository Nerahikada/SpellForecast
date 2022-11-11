<?php

declare(strict_types=1);

use Nerahikada\SpellForecast\Algorithm\PathFinder;
use Nerahikada\SpellForecast\Dictionary\NestedDictionary;
use Nerahikada\SpellForecast\Dictionary\WordsProvider;
use Nerahikada\SpellForecast\Parser\BoardParser;
use Nerahikada\SpellForecast\Path;
use Nerahikada\SpellForecast\Utils\Visualizer;
use Nerahikada\SpellForecast\Word;

require 'vendor/autoload.php';

ini_set('memory_limit', '256M');

$globalDictionary = new NestedDictionary((new WordsProvider())->result);

$board = (new BoardParser())->promptAndParse();
echo Visualizer::board($board) . PHP_EOL;

$algorithm = new PathFinder($board->size);

$continuablePaths = array_map(fn($p) => new Path($p), $algorithm->boardPositions());
$validWords = [];

while ($root = array_shift($continuablePaths)) {
    foreach ($algorithm->generatePath($root) as $path) {
        $word = $board->getWord($path);
        if ($globalDictionary->valid((string)$word)) {
            $validWords[] = $word;
        }
        if ($globalDictionary->continuable((string)$word)) {
            $continuablePaths[] = $path;
        }
    }
}

usort($validWords, fn(Word $a, Word $b): int => $b->point <=> $a->point);

for ($i = 0; $i < 5; ++$i) {
    $word = $validWords[$i];
    echo str_pad('#' . ($i + 1), 3, pad_type: STR_PAD_LEFT);
    echo ": $word->chars ($word->point) ";
    foreach ($word->path as $p) echo "($p->x,$p->y)";
    echo PHP_EOL;
}