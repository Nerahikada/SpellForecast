<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Dictionary;

use Nerahikada\SpellForecast\Utils\SimpleLogger;

final class FilesystemDictionary extends Dictionary
{
    private const DATA_DIR = '.cache/filesystem_dictionary';

    public function __construct(array $words)
    {
        if (file_exists(self::DATA_DIR)) {
            return;
        }

        SimpleLogger::debug("Constructing the file system dictionary...");
        SimpleLogger::debug("Notice: it takes a considerable amount of time");

        mkdir(self::DATA_DIR, recursive: true);

        foreach ($words as $key => $word) {
            if($key !== 0 && $key % 999 === 0) SimpleLogger::debug(($key + 1) . ' files created');
            $path = implode('/', [self::DATA_DIR, ...$this->strSplit($word), self::SENTINEL]);
            if (!file_exists($dir = dirname($path))) {
                mkdir($dir, recursive: true);
            }
            touch($path);
        }

        SimpleLogger::debug("The file system dictionary has been constructed");
    }

    public function continuable(string $word): bool
    {
        $pattern = implode('/', [self::DATA_DIR, ...$this->strSplit($word), '*']);
        return !empty(glob($pattern, GLOB_NOSORT | GLOB_ONLYDIR));
    }

    public function valid(string $word): bool
    {
        $filename = implode('/', [self::DATA_DIR, ...$this->strSplit($word), '0']);
        return file_exists($filename);
    }
}