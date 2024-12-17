<?php

namespace App\Helpers;

class CommentContentHelper
{
    /**
     *
     * @param array $words
     * @return array
     */
    public static function generateCombinations(array $words): array
    {
        $length = count($words);
        $combinations = [];

        // 2^length - 1 kombinacija
        for ($i = 1; $i < (1 << $length); $i++) {
            $subset = [];
            for ($j = 0; $j < $length; $j++) {
                if ($i & (1 << $j)) {
                    $subset[] = strtolower($words[$j]);
                }
            }
            sort($subset);
            $combinations[] = implode(' ', $subset);
        }

        return $combinations;
    }

    /**
     *
     * @param string $contentStr
     * @return string
     */
    public static function makeAbbreviation(string $contentStr): string
    {
        $wordsArray = explode(' ', $contentStr);
        $abbr = '';
        foreach ($wordsArray as $w) {
            $abbr .= $w[0];
        }
        return $abbr;
    }
}
