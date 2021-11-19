<?php
declare(strict_types=1);

namespace Cabana;

use function is_null;
use function str_ireplace;
use function str_replace;
use function trim;
use function ucwords;

class Strings
{
    /**
     * Case-insensitive version of <function>str_replace</function>.
     * @link https://php.net/manual/en/function.str-ireplace.php
     * @param mixed $search <p>
     * Every replacement with search array is
     * performed on the result of previous replacement.
     * </p>
     * @param array|string $replace <p>
     * </p>
     * @param array|string $subject <p>
     * If subject is an array, then the search and
     * replace is performed with every entry of
     * subject, and the return value is an array as
     * well.
     * </p>
     * @param int|null $count [optional] <p>
     * The number of matched and replaced needles will
     * be returned in count which is passed by
     * reference.
     * </p>
     * @return string|string[] a string or an array of replacements.
     */
    public static function strIreplace(array|string $search, array|string $replace, array|string $subject, ?int &$count = null): array|string
    {
        return str_ireplace($search, $replace, $subject, $count);
    }

    /**
     * Uppercase words
     *
     * @param string $string
     * @param string $destSep
     * @param string $srcSep
     * @return string
     */
    public static function ucWords(string $string, string $destSep = '_', string $srcSep = '_'): string
    {
        return str_replace(' ', $destSep, ucwords(str_replace($srcSep, ' ', $string)));
    }

    public static function isBlank(string $string = null): bool
    {
        if (is_null($string) || trim($string) === "") {
            return true;
        }
        return false;
    }
}