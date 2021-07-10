<?php declare(strict_types=1);

namespace WyriHaximus\React\Tests\Inspector\Stream;

final class MonkeyPatchTestTarget
{
    public function bitterbal()
    {
        fwrite(STDOUT, 'kroket');
        fread(STDIN, 13);
        stream_get_contents(STDIN);
    }
}
