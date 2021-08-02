<?php


namespace Cabana;


class Arrays
{
    public static function arrayKeyExists($key, $arr)
    {
        return array_key_exists($key, $arr);
    }
}