<?php

declare(strict_types=1);

use Nerahikada\SpellForecast\Algorithm\PathFinder;
use Nerahikada\SpellForecast\Dictionary\NestedDictionary;
use Nerahikada\SpellForecast\Dictionary\WordsProvider;
use Nerahikada\SpellForecast\Parser\BoardParser;
use Nerahikada\SpellForecast\Path;
use Nerahikada\SpellForecast\Position;
use Nerahikada\SpellForecast\Utils\Visualizer;

require 'vendor/autoload.php';

ini_set('memory_limit', '256M');

$globalDictionary = new NestedDictionary((new WordsProvider())->result);

$board = (new BoardParser())->promptAndParse();
$pathFinder = new PathFinder($board);
echo Visualizer::board($board) . PHP_EOL;

$continuablePaths = array_map(
    fn($n) => new Path(new Position($n % $board->size, (int)($n / $board->size))),
    range(0, $board->size ** 2 - 1)
);
$validWords = [];

while ($root = array_shift($continuablePaths)) {
    foreach ($pathFinder->generatePath($root) as $path) {
        $word = $board->getWord($path);
        if ($globalDictionary->valid((string)$word)) {
            $validWords[] = $word;
        }
        if ($globalDictionary->continuable((string)$word)) {
            $continuablePaths[] = $path;
        }
    }
}

usort($validWords, fn($a, $b): int => $b->point <=> $a->point);

for ($i = 0; $i < 5; ++$i) {
    $word = $validWords[$i];
    echo str_pad('#' . ($i + 1), 3, pad_type: STR_PAD_LEFT);
    echo ": $word->chars ($word->point) ";
    foreach ($word->path as $p) echo "($p->x,$p->y)";
    echo PHP_EOL;
}