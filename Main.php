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

/**
 * @yield Path
 */
function generateAroundPath(Board $board, Path $root) : Generator{
    foreach($root->latest()->around($board->size) as $position){
        if(!$root->has($position)) yield $root->append($position);
    }
}
$TOTAL_COUNT = 0;
function doSomething(Path $path){
    global $TOTAL_COUNT;
    $TOTAL_COUNT++;
}
for($y = 0; $y < $board->size; ++$y){
    for($x = 0; $x < $board->size; ++$x){
        foreach(generateAroundPath($board, new Path(new Position($x, $y))) as $path2){
            doSomething($path2);
            foreach(generateAroundPath($board, $path2) as $path3){
                doSomething($path3);
                foreach(generateAroundPath($board, $path3) as $path4){
                    doSomething($path4);
                    foreach(generateAroundPath($board, $path4) as $path5){
                        doSomething($path5);
                        foreach(generateAroundPath($board, $path5) as $path6){
                            doSomething($path6);
                            foreach (generateAroundPath($board, $path6) as $path7){
                                doSomething($path7);
                                foreach(generateAroundPath($board,$path7) as $path8){
                                    doSomething($path8);
                                    foreach(generateAroundPath($board, $path8) as $path9){
                                        doSomething($path9);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
echo("All done, counts: ");var_dump($TOTAL_COUNT);


exit;
$findAroundPaths = function (array $roots) use ($board): array {
    $paths = [];
    /** @var Path[] $roots */
    foreach ($roots as $root) {
        foreach ($root->current()->around($board->size) as $position) {
            try {
                $paths[] = $root->append($position);
            } catch (InvalidArgumentException) {
            }
        }
    }
    return $paths;
};
$pathsArray = [[new Path(new Position(2, 2))]];
for($length = 2; $length <= 7; ++$length){
    $pathsArray[$length - 1] = $findAroundPaths($pathsArray[$length - 2]);
}
foreach($pathsArray as $length => $paths){
    echo ($length + 1) . " Letters: " . count($paths) . " Paths\n";
    /*
    foreach($paths as $key => $path){
        echo "Path #$key: ";
        foreach($path as $position) echo "($position->x,$position->y) ";
        echo "| " . count($path) . " nodes\n";
    }
    */
}