<?php


namespace Cabana;

use function is_null;

class Scalar
{
    public static function isNull($val): bool
    {
        return is_null($val);
    }
}