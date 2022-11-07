<?php

declare(strict_types=1);

use Nerahikada\SpellForecast\Path;
use Nerahikada\SpellForecast\Position;
use parallel\Channel;
use parallel\Runtime;

require 'vendor/autoload.php';

$runtime = new Runtime('vendor/autoload.php');
$mainToRuntime = new Channel();
$runtimeToMain = new Channel();

$obj = new Position(0, 0);

$runtime->run(function(Position $obj, string $serializedPosition) : void{
    var_dump($obj, unserialize($serializedPosition));
}, [$obj, serialize($obj)]);

//var_dump($runtimeToMain->recv());