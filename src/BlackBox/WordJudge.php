<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\BlackBox;

use Nerahikada\SpellForecast\Word;

final class WordJudge
{
    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36';
    private const BLACK_LISTS = ['FUCK', 'NIGGER', 'SEX',];

    private readonly array $words;

    public function __construct()
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
            $raw = file_get_contents($content['download_url']);
            foreach (explode("\n", $raw) as $line) {
                $line = strtoupper(trim($line));
                if ($line !== '' && ctype_alpha($line) && !in_array($line, self::BLACK_LISTS, true)) {
                    $words[sha1($line, true)] = $line;
                }
            }
        }

        $this->words = $words;
    }

    public function validate(Word $word): bool
    {
        return isset($this->words[sha1($word->chars, true)]);
    }
}