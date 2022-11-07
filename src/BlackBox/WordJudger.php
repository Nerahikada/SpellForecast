<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\BlackBox;

use Nerahikada\SpellForecast\Word;

final class WordJudger
{
    private const BLACK_LISTS = ['FUCK', 'NIGGER', 'SEX',];
    private readonly array $words;

    public function __construct()
    {
        $contents = json_decode(
            file_get_contents('https://api.github.com/repos/jacksonrayhamilton/wordlist-english/contents/sources'),
            true
        );
        foreach ($contents as $content) {
            $raw = file_get_contents($content['download_url']);
            foreach (explode("\n", $raw) as $line) {
                $line = strtoupper(trim($line));
                if ($line !== '' && ctype_alpha($line) && !in_array($line, self::BLACK_LISTS, true)) {
                    // ハッシュ関数の決定(参考): https://stackoverflow.com/a/3665527
                    $this->words[crc32($line)] = $line;
                }
            }
        }
    }

    public function validate(Word $word): bool
    {
        return isset($this->words[crc32($word->chars)]);
    }
}