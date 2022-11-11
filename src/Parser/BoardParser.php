<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Parser;

use Exception;
use Nerahikada\SpellForecast\Board;
use Nerahikada\SpellForecast\Letter;

final class BoardParser
{
    public function promptAndParse(): Board
    {
        do {
            try {
                return $this->parse($this->prompt());
            } catch (ParseException) {
            }
        } while (true);
    }

    /**
     * @throws ParseException
     */
    public function parse(string $string): Board
    {
        $this->parseInternal($string, $letters, $doubleWord);
        try {
            return new Board($letters, $doubleWord);
        } catch (Exception $e) {
            throw new ParseException('Failed to parse the board', previous: $e);
        }
    }

    /**
     * @param Letter[] $letters
     * @param int|null $doubleWord
     */
    private function parseInternal(string $board, array &$letters = null, ?int &$doubleWord = null): void
    {
        $board = implode('', array_values(array_filter(str_split($board), ctype_alnum(...))));

        for ($i = 0; $i < strlen($board); ++$i) {
            $char = $board[$i];
            $multiply = 1;

            if (ctype_digit($board[$i + 1] ?? '')) {
                $multiply = (int)$board[$i + 1];
                ++$i;
            }

            if (ctype_upper($char)) {
                $doubleWord = count($letters);
            }

            $letters[] = new Letter($char, $multiply);
        }
    }

    private function prompt(): string
    {
        fwrite(STDOUT, 'Input board: ');
        return trim(fgets(STDIN));
    }
}