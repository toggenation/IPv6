# IPv6 Utilities

This is me trying to get a handle on IPv6. The aim is to try and understand which bits are getting flipped and how changing  prefixes can effect the subnet addressing

This is **not** finished and doesn't work properly

I'm starting to implement different methods to display IPv6

## Expand a short IPv6 Address to full size

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


/*
ouput
fe80:0000:0000:0000:0000:61ff:fefc:a356
   f   e   8   0    0   0   0   0    0   0   0   0    0   0   0   0    0   0   0   0    6   1   f   f    f   e   f   c    a   3   5   6
1111111010000000 0000000000000000 0000000000000000 0000000000000000 0000000000000000 0110000111111111 1111111011111100 1010001101010110
                                                       * /53
*/
```


## Todo
MAC to IPv6 convertor
Refactor so it makes sense... 
