<?php


namespace Cabana;

use Exception;
use function str_ireplace;

class Strings
{
    public static function strIreplace(array|string $search, array|string $replace, array|string $subject, int &$count = null): array|string
    {
        return str_ireplace($search, $replace, $subject, $count);
    }
}