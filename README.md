# IPv6 Utilities

This is me trying to get a handle on IPv6. The aim is to try and understand which bits are getting flipped and how changing  prefixes can effect the subnet addressing

This is **not** finished and doesn't work properly

I'm starting to implement different methods to display IPv6


## Usage view IPv6 converted to Binary and showing Subnet Prefix Bit length
```sh
composer ip 123::ff/63
```

```sh
> Toggen\Ipv6\Runner::run
       Command line: 123::ff/63
           Expanded: 0123:0000:0000:0000:0000:0000:0000:00ff


   0   1   2   3    0   0   0   0    0   0   0   0    0   0   0   0    0   0   0   0    0   0   0   0    0   0   0   0    0   0   f   f
0000000100100011 0000000000000000 0000000000000000 0000000000000000 0000000000000000 0000000000000000 0000000000000000 0000000011111111
                                                                 * /63

```

## Usage convert MAC Address into IPv6 Local Address
```sh
 composer mac 00:FF:7A:8D:B2:4E
```

Output 

```
> Toggen\Ipv6\Runner::mac
Input: 00:FF:7A:78:92:3E
Output: fe80::2ff7:afff:e789:23e
```

## Expand a shortened IPv6 Address to full size

```php
$ipv6 = new IPv6Util();
$expanded = $ipv6->expand('fe80::61ff:feFC:A356');
# output
fe80:0000:0000:0000:0000:61ff:fefc:a356
```

## Take an expanded IPv6 Address and Format it for display

```php

$expanded = $ipv6->expand('fe80::61ff:feFC:A356');

echo $expanded . PHP_EOL;

$binary = $ipv6->formatHexBinaryPrefix($expanded);

echo $ipv6->displayHexBin($binary) . PHP_EOL;

```


## Todo
MAC to IPv6 convertor
Refactor so it makes sense... 


Refs: https://blog.apnic.net/2018/08/10/how-to-calculating-ipv6-subnets-outside-the-nibble-boundary/

