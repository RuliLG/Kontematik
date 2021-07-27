<?php

namespace App\Services;

class Tokenizer {
    /**
     * Return the number of tokens from a text
     *
     * @param string $text
     * @return int
     */
    public static function count ($text)
    {
        return (new Core())->tokens($text);
    }
}
