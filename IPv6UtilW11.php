<?php


class IPv6UtilW11
{

    public int $prefix;

    public function __construct(public ?string $ip = null)
    {
        if (strpos($ip, '/') !== false) {
            [$ip, $prefix] = explode('/', $ip);

            $this->prefix = intval($prefix);

            $this->padPrefix();
        }

        if (!defined('AF_INET6')) {
            throw new Exception("Inet6 support missing!");
        }


        if (inet_pton($ip) === false) {
            throw new Exception('Invalid IPv6 Address ' . $ip);
        }


        // $hex = inet_ntop(inet_pton($ip));

        // $bin = base_convert(inet_pton($ip), 2, 2);
        // echo $bin . PHP_EOL;
        // echo $hex . PHP_EOL;
    }

    public function expand(string $compressed)
    {
        $compressed = $this->checkValid($compressed);

        $exploded = explode(':', $compressed);

        $newChunk = [];

        foreach ($exploded as $key => $chunk) {
            $newChunk[$key] = $chunk;
            if (strlen($chunk) > 0 && strlen($chunk) < 4) {
                $newChunk[$key] = $this->expandToFour($chunk);
            }
        }



        $compressed_1 =  implode(':', $newChunk);
        $len = strlen($compressed_1);
        $needed = 39 - $len;

        if ($needed) {
            echo $needed . PHP_EOL;
            echo $compressed_1 . PHP_EOL;
            switch ($needed) {
                case 9:
                    // 14 3 LR
                    $compressed_1 = str_replace('::',  ':0000:0000:', $compressed_1);
                    break;
                case 14:
                    // 14 3 LR
                    $compressed_1 = str_replace('::',  ':0000:0000:0000:', $compressed_1);
                    break;
                case 18:
                    // 14 3 LR
                    $compressed_1 = str_replace('::',  ':0000:0000:0000:0000', $compressed_1);
                    break;
                case 19:
                    // 19 4 LR
                    $compressed_1 = str_replace('::',  ':0000:0000:0000:0000:', $compressed_1);
                    break;
                case 23:
                    // 24 5 LR
                    $compressed_1 = str_replace('::',  ':0000:0000:0000:0000:0000', $compressed_1);
                    break;
                case 24:
                    // 24 5 LR
                    $compressed_1 = str_replace('::',  ':0000:0000:0000:0000:0000:', $compressed_1);
                    break;
                case 28:
                    // 28 6 L
                    $compressed_1 = str_replace('::',  ':0000:0000:0000:0000:0000:0000', $compressed_1);
                    break;
                case 29:
                    // 29 6 LR
                    $compressed_1 = str_replace('::',  ':0000:0000:0000:0000:0000:0000:', $compressed_1);
                    break;
                case 33:
                    // 33 7  L
                    $compressed_1 = str_replace('::',  ':0000:0000:0000:0000:0000:0000:0000', $compressed_1);
                    break;
                default:
                    # code...
                    break;
            }
        }

        if (strlen($compressed_1) <> 39) {
            throw new Exception(sprintf('Incorrect uncompressed length of %d. It should be %d', strlen($compressed_1), 39));
        }



        return $compressed_1;
    }

    public function checkValid(string $ip)
    {
        $ip = inet_ntop(inet_pton($ip));

        if ($ip === false) {
            throw new Exception("Invalid IPv6 String : " . $ip);
        }

        return $ip;
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
        if ($this->prefix > 0) {
            return str_repeat(' ', $this->prefix + floor($this->prefix / 16) - 1) . '* /' . $this->prefix;
        }

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

// steps 
// 00-D8-61-FC-A3-56
// convert 00 to binary

// echo base_convert('00', 16, 2) . PHP_EOL;

$ipv6 = new IPv6UtilW11('fe80:0:0:5000:2D8:61ff:feFC:A356/53');

// $expanded = $ipv6->expand('fe80::2d8:61ff:fefc:a356');

// $ip = '2406:3400:31f:2ad1:58fb:39c2:20b3:d62d';

// $expanded = $ipv6->expand($ip);

// $binary = $ipv6->binary($expanded);

// echo $ipv6->displayHexBin($binary) . PHP_EOL;


// echo $ipv6->expand('fe80::fefc:a356') . PHP_EOL;
// echo $ipv6->expand('fe80::a356') . PHP_EOL;
// echo $ipv6->expand('fe80::4:3:2:6') . PHP_EOL;
// echo $ipv6->expand('fe80::3:2:6') . PHP_EOL;
// echo $ipv6->expand('fe80::2:6') . PHP_EOL;
// echo $ipv6->expand('fe80::6') . PHP_EOL;
$expanded = $ipv6->expand('fe80:0:0:5500:2D8:61ff:feFC:A356');
$expanded = $ipv6->expand('fe80::61ff:feFC:A356');

echo $expanded . PHP_EOL;

$binary = $ipv6->formatHexBinaryPrefix($expanded);

echo $ipv6->displayHexBin($binary) . PHP_EOL;
