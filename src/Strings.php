<?php


namespace Cabana;

use function str_ireplace;

class Strings
{
    public static function strIreplace(array|string $search, array|string $replace, array|string $subject, int &$count = null): array|string
    {
        return str_ireplace($search, $replace, $subject, $count);
    }

    public static function ucWords(string $str, string $destSep = '_', string $srcSep = '_')
    {
        return str_replace(' ', $destSep, ucwords(str_replace($srcSep, ' ', $str)));
    }
}