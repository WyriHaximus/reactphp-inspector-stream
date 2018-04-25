<?php

namespace React\Stream;

use WyriHaximus\React\Inspector\GlobalState;

function fread($handle, $length)
{
    $data = \fread($handle, $length);
    GlobalState::incr('io.read', strlen($data));
    return $data;
}

function fwrite($handle, $data)
{
    $writtenLength = \fwrite($handle, $data);
    GlobalState::incr('io.write', $writtenLength);
    return $writtenLength;
}

function stream_get_contents($handle, $length)
{
    $data = \stream_get_contents($handle, $length);
    GlobalState::incr('io.read', strlen($data));
    return $data;
}
