<?php declare(strict_types=1);

namespace React\Stream;

use WyriHaximus\React\Inspector\GlobalState;

function fread($handle, $length)
{
    $data = \fread($handle, $length);
    GlobalState::incr('eventloop.io.read', (float)strlen($data));

    return $data;
}

function fwrite($handle, $data, $length = null)
{
    $writtenLength = \fwrite($handle, $data, $length);
    GlobalState::incr('eventloop.io.write', (float)$writtenLength);

    return $writtenLength;
}

function stream_get_contents($handle, $length)
{
    $data = \stream_get_contents($handle, $length);
    GlobalState::incr('eventloop.io.read', (float)strlen($data));

    return $data;
}
