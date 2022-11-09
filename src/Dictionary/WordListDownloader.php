<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Dictionary;

use Nerahikada\SpellForecast\Utils\SimpleLogger;

final class WordListDownloader
{
    /** @var string[] */
    public readonly array $result;

    public function __construct(
        string $repository = 'jacksonrayhamilton/wordlist-english',
        string $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36'
    ) {
        SimpleLogger::debug("Fetching contents from $repository...");
        $contents = json_decode(
            file_get_contents(
                'https://api.github.com/repos/' . $repository . '/contents/sources',
                context: stream_context_create(['http' => ['user_agent' => $userAgent]])
            ),
            true,
            flags: JSON_THROW_ON_ERROR
        );
        SimpleLogger::debug("Fetched " . count($contents) . " contents");

        $words = [];

        foreach ($contents as $content) {
            SimpleLogger::debug("Downloading file \"{$content["name"]}\"...");
            foreach (explode("\n", file_get_contents($content['download_url'])) as $line) {
                if (ctype_alpha($line = strtolower(trim($line)))) {
                    $words[] = $line;
                }
            }
        }

        SimpleLogger::debug("Fetched " . count($words) . " words");
        $this->result = array_unique($words);
        SimpleLogger::debug("Found " . count($this->result) . " unique words");
    }
}