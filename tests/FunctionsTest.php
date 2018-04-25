<?php declare(strict_types=1);

namespace WyriHaximus\React\Tests\Inspector\Stream;

use PHPUnit\Framework\TestCase;
use React\Stream;
use WyriHaximus\React\Inspector\GlobalState;

final class FunctionsTest extends TestCase
{
    public function testFread()
    {
        GlobalState::clear();
        $handle = fopen('php://memory', 'a+');
        fwrite($handle, 'abc', 3);
        self::assertSame([], GlobalState::get());
        Stream\fread($handle, 3);
        self::assertSame(['io.read' => 0], GlobalState::get());
        rewind($handle);
        Stream\fread($handle, 3);
        self::assertSame(['io.read' => 3], GlobalState::get());
        fclose($handle);
    }

    public function testStreamGetContents()
    {
        GlobalState::clear();
        $handle = fopen('php://memory', 'a+');
        fwrite($handle, 'abc', 3);
        self::assertSame([], GlobalState::get());
        Stream\stream_get_contents($handle, 3);
        self::assertSame(['io.read' => 0], GlobalState::get());
        rewind($handle);
        Stream\stream_get_contents($handle, 3);
        self::assertSame(['io.read' => 3], GlobalState::get());
        fclose($handle);
    }

    public function testFwrite()
    {
        GlobalState::clear();
        $handle = fopen('php://memory', 'a+');
        self::assertSame([], GlobalState::get());
        Stream\fwrite($handle, 'abc', 3);
        self::assertSame(['io.write' => 3], GlobalState::get());
        fclose($handle);
    }
}
