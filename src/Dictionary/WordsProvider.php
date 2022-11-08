<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Dictionary;

use Nerahikada\SpellForecast\Utils\SimpleLogger;

final class WordsProvider
{
    /** @var string[] */
    public readonly array $result;

    public function __construct()
    {
        if (!file_exists('.cache')) {
            mkdir('.cache');
        }
        if(!file_exists('.cache/dictionary.txt')){
            $words = (new WordListDownloader())->result;
            $this->sort($words);
            file_put_contents('.cache/dictionary.txt', implode("\n", $words));
        }else{
            $words = explode("\n", file_get_contents('.cache/dictionary.txt'));
        }

        SimpleLogger::debug("Loaded " . count($words) . " words");
        $this->result = $words;
    }

    private function sort(array &$words): void
    {
        sort($words, SORT_STRING);
        usort($words, fn(string $a, string $b): int => strlen($a) <=> strlen($b));
    }
}