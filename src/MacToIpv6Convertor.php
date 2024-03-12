<?php

namespace Toggen\Ipv6;

use Exception;

class MacToIpv6Convertor
{
    use UtilTrait;
    public function __construct(public ?string $mac)
    {
        if (!filter_var($mac, FILTER_VALIDATE_MAC)) {
            throw new Exception("Please specify a valid MAC Address");
        }
        echo $this->padLeft("Input: ", 20) . "$mac\n";
        echo $this->padLeft("MAC to IPv6: ", 20) . $this->convert($mac) . "\n";
    }

    public function convert(string $mac)
    {
        //00-FF-7A-8D-B2-4E
        //00:FF:7A:8D:B2:4E
        //c42f.90e7.6f53
        //f4:92:bf:84:20:1b
        $mac = $this->normalizeMac($mac);

        $ip = $this->buildIpv6FromMac($mac);

        return $ip;
    }

    private function flipSeventhBit($firstChunk)
    {

        $bitmask = hexdec($firstChunk);

        $flipped =  $bitmask ^= 2;
        $hex = dechex($flipped);

        return $hex;
    }

    private function insertIpv6Elements($chunks)
    {
        array_splice($chunks, 3, 0, ['ff', 'fe']);

        $str = implode("", $chunks);

        $strSplit = str_split($str, 4);

        $joined = implode(":", $strSplit);

        $fullIp =  sprintf('fe80::%s', $joined);

        return $fullIp;
    }

    private function buildIpv6FromMac($mac)
    {
        $chunks = explode(':', $mac);

        $chunks[0] = $this->flipSeventhBit($chunks[0]);

        return $this->insertIpv6Elements($chunks);
    }

    /**
     * Takes different formats of mac and returns 
     * 00-FF-7A-8D-B2-4E
     * 00:FF:7A:8D:B2:4E
     * c42f.90e7.6f53
     * 
     * 
     * 
     * @param string $mac 
     * @return string 
     */
    private function normalizeMac(string $mac)
    {
        $mac = strtolower($mac);

        $stripDelimeters = str_replace([":", '-', '.'], '', $mac);

        $normalized = implode(":", str_split($stripDelimeters, 2));

        return $normalized;
    }
}
