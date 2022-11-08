<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Utils;

use DateTimeImmutable;
use ReflectionClass;

final class SimpleLogger
{
    public static function debug(string $message): void
    {
        $class = debug_backtrace(!DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['class'];
        $class = (new ReflectionClass($class))->getShortName();
        fwrite(STDOUT, self::timestamp() . ' | ' . $class . " >> " . $message . PHP_EOL);
    }

    private static function timestamp(): string
    {
        return (new DateTimeImmutable())->format('H:i:s.v');
    }
}