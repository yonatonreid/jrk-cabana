<?php


namespace Cabana;

use function chmod;
use function is_dir;
use function mkdir;

class Fs
{
    public static function mkdir(string $path, int $perms = 0777, bool $recursive = true): void
    {
        mkdir($path, $perms, $recursive);
    }

    public static function isDir(string $path): bool
    {
        return is_dir($path);
    }

    public static function isReadable(string $path): bool
    {
        return is_readable($path);
    }

    public static function chmod(string $path, int $perms = 0777): void
    {
        chmod($path, $perms);
    }
}