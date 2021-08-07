<?php


namespace Cabana;


class Arrays
{
    public static function arrayKeyExists($key, $arr)
    {
        return array_key_exists($key, $arr);
    }

    public static function pluckMultipleFlattened(array $columns, array $collection): array
    {
        $final = [];
        foreach ($columns as $col) {
            $data = \Underscore\Types\Arrays ::pluck($collection, $col);
            $final[$col] = $data[0];
        }
        return $final;
    }
}