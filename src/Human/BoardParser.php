<?php

declare(strict_types=1);

namespace Nerahikada\SpellForecast\Human;

use Exception;
use Nerahikada\SpellForecast\Board;
use Nerahikada\SpellForecast\Letter;

/**
 * ダブルレター(DL)/トリプルレター(TL): 文字の後ろに数字
 * ダブルワード(2X): 大文字
 *
 * A     A     A(DL) ;
 * B(2X) B     B     ;
 * C     C     C     ;
 * = "aaa2Bbbccc"
 */
final class BoardParser
{
    private Board $board;

    public function __construct()
    {
        /** @noinspection PhpStatementHasEmptyBodyInspection */
        while (!$this->input()) {
        }
    }

    private function input(): bool
    {
        echo 'Input board: ';
        $handle = fopen('php://stdin', 'r');
        $inputs = trim(fgets($handle));
        fclose($handle);

        $this->parse($inputs, $letters, $doubleWord);

        try {
            $board = new Board($letters, $doubleWord);
            $this->board = $board;
            return true;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * @param Letter[] $letters
     * @param int|null $doubleWord
     */
    private function parse(string $input, array &$letters = null, ?int &$doubleWord = null): void
    {
        $input = implode('', array_values(array_filter(str_split($input), ctype_alnum(...))));

        for ($i = 0; $i < strlen($input); ++$i) {
            $char = $input[$i];
            $multiply = 1;

            if (ctype_digit($input[$i + 1] ?? '')) {
                $multiply = (int)$input[$i + 1];
                ++$i;
            }

            $letters[] = new Letter($char, $multiply);

            if (ctype_upper($char)) {
                $doubleWord = $i;
            }
        }
    }

    public function result(): Board
    {
        return $this->board;
    }
}