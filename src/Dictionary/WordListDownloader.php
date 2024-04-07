<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Dictionary;

use Nerahikada\SpellForecast\Utils\SimpleLogger;
use RuntimeException;
use stdClass;

final class WordListDownloader
{
    public function __construct(
        private readonly string $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36'
    ) {
    }

    public function get(): array
    {
        /** @var string[] $words */
        $words = [];

        foreach ($this->fetchContents() as $content) {
            SimpleLogger::debug("Downloading file \"$content->name\"...");
            foreach (explode("\n", file_get_contents($content->download_url)) as $line) {
                if (ctype_alnum($word = trim($line)) && strlen($line) > 1) {
                    $words[] = $word;
                }
            }
        }

        return $words;
    }

    /**
     * @return stdClass[]
     */
    private function fetchContents(): array
    {
        SimpleLogger::debug("Fetching contents from GitHub...");

        $contents = json_decode(
            file_get_contents(
                'https://api.github.com/repos/jacksonrayhamilton/wordlist-english/contents/sources',
                context: stream_context_create(['http' => ['user_agent' => $this->userAgent]])
            )
        );

        if (!is_array($contents) || !is_a(reset($contents), stdClass::class)) {
            throw new RuntimeException('Failed to fetch contents');
        }

        SimpleLogger::debug("Fetched " . count($contents) . " contents");

        return $contents;
    }
}
