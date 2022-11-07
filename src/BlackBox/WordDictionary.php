<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\BlackBox;

use Nerahikada\SpellForecast\Word;

final class WordDictionary
{
    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36';
    private const BLACK_LIST = ['FUCK', 'NIGGER', 'SEX',];

    private readonly array $words;

    public function __construct()
    {
        if (!file_exists('.cache')) {
            mkdir('.cache');
        }
        if (!file_exists('.cache/dictionary.txt')) {
            $this->downloadFromInternet();
        }

        $words = [];
        $handle = fopen('.cache/dictionary.txt', 'r');
        while(($line = fgets($handle)) !== false){
            $word = trim($line);
            $words[sha1($word, true)] = $word;
        }
        $this->words = $words;
    }

    private function downloadFromInternet(): void
    {
        $contents = json_decode(
            file_get_contents(
                'https://api.github.com/repos/jacksonrayhamilton/wordlist-english/contents/sources',
                context: stream_context_create(['http' => ['user_agent' => self::USER_AGENT]])
            ),
            true
        );

        $words = [];

        foreach ($contents as $content) {
            foreach (explode("\n", file_get_contents($content['download_url'])) as $line) {
                $line = strtoupper(trim($line));
                if ($line !== '' && ctype_alpha($line) && !in_array($line, self::BLACK_LIST, true)) {
                    $words[] = $line;
                }
            }
        }

        file_put_contents('.cache/dictionary.txt', implode("\n", $words));
    }

    public function contain(Word $word): bool
    {
        return isset($this->words[sha1($word->chars, true)]);
    }
}