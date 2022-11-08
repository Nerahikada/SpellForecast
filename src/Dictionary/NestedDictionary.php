<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Dictionary;

final class NestedDictionary
{
    private readonly array $nestedArray;

    /**
     * @param string[] $words
     * @link https://stackoverflow.com/a/25536353
     */
    public function __construct(array $words)
    {
        $nested = [];

        foreach ($words as $word) {
            $tmp = &$nested;
            foreach (str_split($word . "\0") as $key) {
                $tmp = &$tmp[$key];
            }

            $tmp = true;
        }

        assert(is_array($nested));
        $this->nestedArray = $nested;
    }

    public function valid(string $word): bool
    {
        $current = $this->nestedArray;
        foreach (str_split($word . "\0") as $key) {
            if (!isset($current[$key])) {
                return false;
            } elseif (true === $current = $current[$key]) {
                return true;
            }
        }
        assert(false);
    }

    public function continuable(string $word): bool
    {
        $current = $this->nestedArray;
        foreach (str_split($word) as $key) {
            if (!isset($current[$key])) {
                return false;
            }
            $current = $current[$key];
        }
        return is_array($current) && (count($current) > 1 || !isset($current["\0"]));
    }
}