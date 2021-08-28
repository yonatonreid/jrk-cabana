<?php


namespace Cabana;

use JetBrains\PhpStorm\Pure;
use function array_change_key_case;
use function array_key_exists;

class Arrays
{
    public static function arrayKeyExists($key, $arr): bool
    {
        return array_key_exists($key, $arr);
    }

    public static function pluckMultipleFlattened(array $columns, array $collection): array
    {
        $final = [];
        foreach ($columns as $col) {
            $data = \Underscore\Types\Arrays ::pluck([$collection], $col);
            $final[$col] = $data[0];
        }
        return $final;
    }

    #[Pure] public static function arrayChangeKeyCaseUpper(array $array): array
    {
        return static ::arrayChangeKeyCase($array, CASE_UPPER);
    }

    #[Pure] public static function arrayChangeKeyCaseLower(array $array): array
    {
        return static ::arrayChangeKeyCase($array);
    }

    public static function arrayChangeKeyCaseRecursiveUpper(array $array): array
    {
        return static ::arrayChangeKeyCaseRecursive($array, CASE_UPPER);
    }

    public static function arrayChangeKeyCaseRecursiveLower(array $array): array
    {
        return static ::arrayChangeKeyCaseRecursive($array);
    }

    public static function arrayChangeKeyCaseRecursive(array $array, int $case = CASE_LOWER): array
    {
        return static ::arrayMap(function ($item) use ($case) {
            if (is_array($item)) {
                $item = static ::arrayChangeKeyCaseRecursive($item, $case);
            }
            return $item;
        }, static ::arrayChangeKeyCase($array, $case));
    }

    private static function arrayChangeKeyCase(array $array, int $case = CASE_LOWER): array
    {
        return array_change_key_case($array, $case);
    }

    public static function arrayMap(callable $callable, array $array, array ...$arrays): array
    {
        return call_user_func_array('array_map', func_get_args());
    }

    public static function arrayMerge(array $array1, array $array2, ...$arrays)
    {
        return call_user_func_array('array_merge', func_get_args());
    }

}