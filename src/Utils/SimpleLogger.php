<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Utils;

use DateTimeImmutable;
use ReflectionClass;

final class SimpleLogger
{
    public static function debug(string $message): void
    {
        $trace = debug_backtrace(!DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $trace = $trace[1] ?? $trace[0];
        $caller = isset($trace['class']) ? (new ReflectionClass($trace['class']))->getShortName() : $trace['method'];
        fwrite(STDOUT, self::timestamp() . ' | ' . $caller . " >> " . $message . PHP_EOL);
    }

    private static function timestamp(): string
    {
        return (new DateTimeImmutable())->format('H:i:s.v');
    }
}