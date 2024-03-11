<?php

namespace Toggen\Ipv6;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class Runner
{
    public static function run(Event $event)
    {
        if (count($event->getArguments()) > 0) {
            $ip = $event->getArguments()[0];

            $ipv6 = new IPv6Util($ip);
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
