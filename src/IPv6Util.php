<?php

namespace Toggen\Ipv6;

use Exception;

class IPv6Util
{

    public int $prefix;

    public function __construct(public ?string $ip = null)
    {

        if (is_null($ip)) {
            throw new Exception("You need to pass an IPv6 address to the constructor of this class");
        }

        $this->validateInput($ip);

        echo $this->padLeft('Command line:', 20) . ' ' . $ip . PHP_EOL;

        if (strpos($ip, '/') !== false) {
            [$ip, $prefix] = explode('/', $ip);

            $this->validatePrefix($prefix);

            $this->prefix = $prefix;
        } else {
            $this->prefix = 64;
        }

        if (!defined('AF_INET6')) {
            throw new Exception("Inet6 support missing!");
        }

        if (inet_pton($ip) === false) {
            throw new Exception('Invalid IPv6 Address ' . $ip);
        }

        $expanded = $this->expand($ip);
        $formatted = $this->formatHexBinaryPrefix($expanded);

        echo $this->padLeft('Expanded:', 20) . ' ' . $expanded . PHP_EOL;
        echo str_repeat(PHP_EOL, 2);
        echo $this->displayHexBin($formatted) . PHP_EOL;
    }

    private function validateInput(string $ip)
    {
        $validCharsOnly = '0123456789abcdef:/';
        $validChars = str_split($validCharsOnly);

        foreach (str_split($ip) as $char) {
            if (!in_array($char, $validChars)) {
                throw new Exception("Invalid characters. Only " . $validCharsOnly . ' allowed');
            }
        }
    }

    private function validatePrefix(mixed $prefix)
    {
        if (!ctype_digit($prefix)) {
            throw new Exception('Invalid prefix value ' . $prefix);
        }
        if ($prefix < 1 || $prefix > 128) {
            throw new Exception("Invalid prefix length $prefix");
        }
    }

    private function padLeft($message, int $padding = 20)
    {
        return str_pad(string: $message, length: $padding, pad_type: STR_PAD_LEFT);
    }

    private function makeAllHextetsFourLong(string $ip)
    {
        $exploded = explode(':', $ip);

        $newChunk = [];

        foreach ($exploded as $key => $chunk) {
            $newChunk[$key] = $chunk;
            if (strlen($chunk) > 0 && strlen($chunk) < 4) {
                $newChunk[$key] = $this->expandToFour($chunk);
            }
        }

        return implode(':', $newChunk);
    }

    public function expand(string $shortenedIp)
    {
        $this->checkValid($shortenedIp);

        $blocksOfFour = $this->makeAllHextetsFourLong($shortenedIp);



        return $this->replace($blocksOfFour);
    }

    private function replace(string $ip)
    {
        $needed = 39 - strlen($ip);

        if ($needed === 0) {
            return $ip;
        }

        $repeats =  floor($needed / 5);

        $replace = str_repeat(':0000', $repeats + 1);

        if (!str_ends_with($ip, ':')) {
            $replace .= ':';
        }

        $replaced = str_replace('::', $replace, $ip);

        if (strlen($replaced) <> 39) {
            throw new Exception(sprintf('Incorrect uncompressed length of %d. It should be %d', strlen($replaced), 39));
        }

        return $replaced;
    }

    public function checkValid(string $ip)
    {
        $ip = inet_ntop(inet_pton($ip));

        if ($ip === false) {
            throw new Exception("Invalid IPv6 String : " . $ip);
        }
    }

    public function expandToFour(string $chunk)
    {
        return sprintf('%04s', $chunk);
    }

    public function formatHexBinaryPrefix(string $expandedIp)
    {
        $chunks = [];
        foreach (explode(":", $expandedIp) as $key => $chunk) {
            $chunks['hex'][$key] = implode('', array_map(function ($chr) {
                return str_pad($chr, 4, ' ', STR_PAD_LEFT);
            }, str_split($chunk)));
            $chunks['binary'][$key] = sprintf('%016s', base_convert($chunk, 16, 2));
            $chunks['prefix'] = $this->padPrefix();
            //11111110100000000000000000000000000000000000000000000000000000000000001011011000011000011111111111111110111111001010001101010110
        }

        return $chunks;
    }

    public function padPrefix()
    {
        $adjust = 0;

        if ($this->prefix % 16 === 0) {
            $adjust = -1;
        }

        return str_repeat(' ', $this->prefix + floor($this->prefix / 16) - 1 + $adjust) . '* /' . $this->prefix;


        return '';
    }

    public function displayHexBin(array $chunks)
    {
        $lines = [];
        $lines[] = implode(' ', $chunks['hex']);
        $lines[] = implode(' ', $chunks['binary']);
        $lines[] = $chunks['prefix'];

        return implode("\n", $lines);
    }
    //0000:0000:0000:0000:0000:0000:0000:0000

    // public 


}
