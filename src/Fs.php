<?php


namespace Cabana;

use DirectoryIterator;
use Exception;
use function chmod;
use function is_dir;
use function is_readable;
use function mkdir;
use function rmdir;

class Fs
{
    public static function fileExists(string $file): bool
    {
        return is_file($file) && file_exists($file);
    }

    public static function fixDirectorySeparator(string $path): string
    {
        return Strings ::strIreplace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public static function mkdir(string $path, int $perms = 0777, bool $recursive = true): void
    {
        mkdir($path, $perms, $recursive);
    }

    public static function rmdir(string $path): void
    {
        rmdir($path);
    }

    public static function isDir(string $path): bool
    {
        return is_dir($path);
    }

    public static function mkDirIfNotExists(string $path, int $perms = 0777, bool $recursive = true): void
    {
        if (!static ::isDir($path)) {
            static ::mkdir($path, $perms, $recursive);
        }
    }

    /**
     * @throws Exception
     */
    public static function makeReadable(string $path, int $perms = 0777): void
    {
        if (!static ::isReadable($path)) {
            static ::chmod($path, $perms);
        }
    }

    public static function isReadable(string $path): bool
    {
        return is_readable($path);
    }

    /**
     * @throws Exception
     */
    public static function chmod(string $path, int $perms = 0777): void
    {
        chmod($path, $perms);
    }

    public static function chmodR($path, int $perms = 0777)
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