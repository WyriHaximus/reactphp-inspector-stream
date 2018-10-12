<?php declare(strict_types=1);

namespace WyriHaximus\React\Tests\Inspector\Stream;

use PhpParser\PrettyPrinter\Standard;
use PHPUnit\Framework\TestCase;
use React\Stream\WritableResourceStream;
use WyriHaximus\React\Inspector\Stream\Monky;

final class MonkeyTest extends TestCase
{
    public function testRerouting(): void
    {
        $code = (new Standard())->prettyPrint(Monky::patch(WritableResourceStream::class)->getAst()->stmts);
        self::assertNotFalse(strpos($code, 'React\Stream\fwrite'));
    }
}
