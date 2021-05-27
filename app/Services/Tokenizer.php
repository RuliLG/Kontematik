<?php

namespace App\Services;

class Tokenizer {
    /**
     * Return the number of tokens from a text
     *
     * @param string $text
     * @return int
     */
    static function count ($text)
    {
        // 1 token = 4 characters
        return ceil(strlen($text) / 4);
    }
}
