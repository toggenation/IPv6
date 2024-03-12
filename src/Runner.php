<?php

namespace Toggen\Ipv6;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class Runner
{
    use UtilTrait;

    public static function run(Event $event)
    {
        if (count($event->getArguments()) > 0) {
            $ip = $event->getArguments()[0];

            (new IPv6Util($ip))->run();
        } else {
            echo "Please specify an IPv6 address on the command line\n";
        }
    }

    public static function expand(Event $event)
    {
        if (count($event->getArguments()) > 0) {
            $ip = $event->getArguments()[0];

            echo self::padLeft('Output:', 20) . ' ' . (new IPv6Util($ip))->expand() . PHP_EOL;
        } else {
            echo "Please specify an IPv6 address on the command line\n";
        }
    }


    public static function mac(Event $event)
    {
        if (count($event->getArguments()) > 0) {
            $mac = $event->getArguments()[0];

            $mac = new MacToIpv6Convertor($mac);
        } else {
            echo "Please specify a valid MAC address on the command line\n";
        }
    }
}
