<?php

namespace Toggen\Ipv6;

trait UtilTrait
{
    private static function padLeft($message, int $padding = 20)
    {
        return str_pad(string: $message, length: $padding, pad_type: STR_PAD_LEFT);
    }
}
