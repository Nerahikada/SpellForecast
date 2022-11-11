<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Dictionary;

abstract class Dictionary
{
    protected const SENTINEL = '0';

    /** @param string[] $words */
    abstract public function __construct(array $words);

    abstract public function continuable(string $word): bool;

    abstract public function valid(string $word): bool;

    protected function strSplit(string $string): array
    {
        return mb_str_split(strtolower($string));
    }
}