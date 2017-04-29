<?php declare(strict_types=1);

namespace WyriHaximus\React\Inspector\F42;

final class GlobalState
{
    static protected $state = [];

    public static function get(): array
    {
        return self::$state;
    }

    public static function reset()
    {
        self::$state = [];
    }

    public static function set(string $key, int $value)
    {
        self::$state[$key] = $value;
    }

    public static function incr(string $key, int $value = 1)
    {
        if (!isset(self::$state[$key])) {
            self::$state[$key] = 0;
        }

        self::$state[$key] += $value;
    }
}
