<?php
declare(strict_types=1);

namespace Cabana;

use function mb_convert_case;
use function mb_internal_encoding;
use function mb_language;
use function mb_strtolower;
use function mb_strtoupper;
use function str_ireplace;
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
    public static function strIreplace(array|string $search, array|string $replace, array|string $subject, ?int &$count): array|string
    {
        return str_ireplace($search, $replace, $subject, $count);
    }

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

    /**
     * Perform case folding on a string
     * @link https://php.net/manual/en/function.mb-convert-case.php
     * @param string $string <p>
     * The string being converted.
     * </p>
     * @param int $mode <p>
     * The mode of the conversion. It can be one of
     * MB_CASE_UPPER,
     * MB_CASE_LOWER, or
     * MB_CASE_TITLE.
     * </p>
     * @param string|null $encoding [optional]
     * @return string A case folded version of string converted in the
     * way specified by mode.
     */
    #[Pure]
    public static function mbConvertCase(string $string, int $mode, ?string $encoding): string
    {
        return mb_convert_case($string, $mode, $encoding);
    }

    /**
     * Make a string lowercase
     * @link https://php.net/manual/en/function.mb-strtolower.php
     * @param string $string <p>
     * The string being lowercased.
     * </p>
     * @param string|null $encoding [optional]
     * @return string str with all alphabetic characters converted to lowercase.
     */
    #[Pure]
    public static function mbStrToLower(string $string, ?string $encoding): string
    {
        return mb_strtolower($string, $encoding);
    }

    /**
     * Make a string uppercase
     * @link https://php.net/manual/en/function.mb-strtoupper.php
     * @param string $string <p>
     * The string being uppercased.
     * </p>
     * @param string|null $encoding [optional]
     * @return string str with all alphabetic characters converted to uppercase.
     */
    #[Pure]
    public static function mbStrToUpper(string $string, ?string $encoding): string
    {
        return mb_strtoupper($string, $encoding);
    }

    /**
     * Set/Get current language
     * @link https://php.net/manual/en/function.mb-language.php
     * @param string|null $language [optional] <p>
     * Used for encoding
     * e-mail messages. Valid languages are "Japanese",
     * "ja","English","en" and "uni"
     * (UTF-8). mb_send_mail uses this setting to
     * encode e-mail.
     * </p>
     * <p>
     * Language and its setting is ISO-2022-JP/Base64 for
     * Japanese, UTF-8/Base64 for uni, ISO-8859-1/quoted printable for
     * English.
     * </p>
     * @return bool|string If language is set and
     * language is valid, it returns
     * true. Otherwise, it returns false.
     * When language is omitted, it returns the language
     * name as a string. If no language is set previously, it then returns
     * false.
     */
    public static function mbLanguage(?string $language): string|bool
    {
        return mb_language($language);
    }

    /**
     * Set/Get internal character encoding
     * @link https://php.net/manual/en/function.mb-internal-encoding.php
     * @param string|null $encoding [optional] <p>
     * encoding is the character encoding name
     * used for the HTTP input character encoding conversion, HTTP output
     * character encoding conversion, and the default character encoding
     * for string functions defined by the mbstring module.
     * </p>
     * @return bool|string If encoding is set, then
     * true on success or false on failure.
     * If encoding is omitted, then
     * the current character encoding name is returned.
     */
    public static function mbInternalEncoding(?string $encoding): string|bool
    {
        if (is_null($encoding)) {
            return mb_internal_encoding();
        }
        return mb_internal_encoding($encoding);
    }

    /**
     * Detect HTTP input character encoding
     * @link https://php.net/manual/en/function.mb-http-input.php
     * @param string|null $type [optional] <p>
     * Input string specifies the input type.
     * "G" for GET, "P" for POST, "C" for COOKIE, "S" for string, "L" for list, and
     * "I" for the whole list (will return array).
     * If type is omitted, it returns the last input type processed.
     * </p>
     * @return array|false|string The character encoding name, as per the type.
     * If mb_http_input does not process specified
     * HTTP input, it returns false.
     */
    #[Pure]
    public static function mbHttpInput(?string $type): array|string|false
    {
        return mb_http_input($type);
    }
}