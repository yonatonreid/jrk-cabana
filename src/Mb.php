<?php

namespace Cabana;

use function mb_convert_case;
use function mb_http_input;
use function mb_http_output;
use function mb_internal_encoding;
use function mb_language;
use function mb_regex_encoding;
use function mb_strtolower;
use function mb_strtoupper;

class Mb
{
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
    public static function mbInternalEncoding(?string $encoding = null): string|bool
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

    /**
     * Set/Get HTTP output character encoding
     * @link https://php.net/manual/en/function.mb-http-output.php
     * @param string|null $encoding [optional] <p>
     * If encoding is set,
     * mb_http_output sets the HTTP output character
     * encoding to encoding.
     * </p>
     * <p>
     * If encoding is omitted,
     * mb_http_output returns the current HTTP output
     * character encoding.
     * </p>
     * @return bool|string If encoding is omitted,
     * mb_http_output returns the current HTTP output
     * character encoding. Otherwise,
     * true on success or false on failure.
     */
    public static function mbHttpOutput(?string $encoding): string|bool
    {
        return mb_http_output($encoding);
    }

    /**
     * Returns current encoding for multibyte regex as string
     * @link https://php.net/manual/en/function.mb-regex-encoding.php
     * @param string|null $encoding [optional]
     * @return bool|string If encoding is set, then Returns TRUE on success
     * or FALSE on failure. In this case, the internal character encoding
     * is NOT changed. If encoding is omitted, then the current character
     * encoding name for a multibyte regex is returned.
     */
    public static function mbRegexEncoding(?string $encoding = null): string|bool
    {
        return mb_regex_encoding($encoding);
    }

    /**
     * Get string length
     * @link https://php.net/manual/en/function.mb-strlen.php
     * @param string $string <p>
     * The string being checked for length.
     * </p>
     * @param string|null $encoding [optional]
     * @return int|false the number of characters in
     * string str having character encoding
     * encoding. A multi-byte character is
     * counted as 1.
     */
    public static function mbStrlen(string $string, ?string $encoding = null): int
    {
        return mb_strlen($string, $encoding);
    }
}