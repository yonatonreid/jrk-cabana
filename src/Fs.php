<?php
/**
 * @author jrk <me at aroadahead.com>
 * @copyright 2021 A Road Ahead, LLC
 * @license Apache 2.0
 */
declare(strict_types=1);

/**
 * @package \Cabana
 */

namespace Cabana;

/**
 * Import Statements
 */

use DirectoryIterator;
use Exception;
use function chmod;
use function is_dir;
use function is_readable;
use function mkdir;
use function rmdir;
use function file_exists;

/**
 * Class Fs
 *
 * @final
 * @package \Cabana
 */
final class Fs
{
    /**
     * File Exists?
     *
     * @param string $file
     * @return bool
     */
    public static function fileExists(string $file): bool
    {
        return is_file($file) && file_exists($file);
    }

    /**
     * Fix Directory Separator
     *
     * @param string $path
     * @return string
     */
    public static function fixDirectorySeparator(string $path): string
    {
        return Strings ::strIreplace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Make directory
     *
     * @param string $path
     * @param int $perms
     * @param bool $recursive
     * @return void
     */
    public static function mkdir(string $path, int $perms = 0777, bool $recursive = true): void
    {
        mkdir($path, $perms, $recursive);
    }

    /**
     * Remove directory
     *
     * @param string $path
     * @return void
     */
    public static function rmdir(string $path): void
    {
        rmdir($path);
    }

    /**
     * Is Directory?
     *
     * @param string $path
     * @return bool
     */
    public static function isDir(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * Make directory if not exists
     *
     * @param string $path
     * @param int $perms
     * @param bool $recursive
     */
    public static function mkDirIfNotExists(string $path, int $perms = 0777, bool $recursive = true): void
    {
        if (!static ::isDir($path)) {
            static ::mkdir($path, $perms, $recursive);
        }
    }

    /**
     * Make readable
     *
     * @throws Exception
     */
    public static function makeReadable(string $path, int $perms = 0777): void
    {
        if (!static ::isReadable($path)) {
            static ::chmod($path, $perms);
        }
    }

    /**
     * Is readable?
     *
     * @param string $path
     * @return bool
     */
    public static function isReadable(string $path): bool
    {
        return is_readable($path);
    }

    /**
     * Chmod
     *
     * @throws Exception
     */
    public static function chmod(string $path, int $perms = 0777): void
    {
        chmod($path, $perms);
    }

    /**
     * Chmod Recursive
     *
     * @param $path
     * @param int $perms
     * @throws Exception
     */
    public static function chmodR($path, int $perms = 0777): void
    {
        $dir = new DirectoryIterator($path);
        foreach ($dir as $item) {
            static ::chmod($item -> getPathname(), $perms);
            if ($item -> isDir() && !$item -> isDot()) {
                static ::chmodR($item -> getPathname(), $perms);
            }
        }
    }
}