<?php


namespace Cabana;

use function str_ireplace;

class Strings
{
    public static function strIReplace(array|string $search, array|string $replace, array|string $subject, &$count): array|string
    {
        return str_ireplace($search, $replace, $subject, $count);
    }
}