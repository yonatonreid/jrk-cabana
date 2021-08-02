<?php


namespace Cabana;

use Exception;
use function str_ireplace;

class Strings
{
    public static function strIreplace(array|string $search, array|string $replace, array|string $subject, $count = null): array|string
    {
        if (is_null($count)) {
            return str_ireplace($search, $replace, $subject);
        }
        if (!is_int($count)) {
            throw new Exception("Count must be an integer.");
        }
        return str_ireplace($search, $replace, $subject, $count);
    }
}