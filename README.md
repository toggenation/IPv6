# IPv6 Utilities

This is me trying to get a handle on IPv6. 

The aim is to try and understand which bits are getting flipped and how changing prefixes and hextet values can effect the subnet addressing

# Install

clone this repo

```
composer install
```


## Usage 

### View IPv6 converted to Binary and showing Subnet Prefix Bit length
```sh
composer ip 123::ff/63
```

Output

```sh
> Toggen\Ipv6\Runner::run
       Command line: 123::ff/63
           Expanded: 0123:0000:0000:0000:0000:0000:0000:00ff


   0   1   2   3    0   0   0   0    0   0   0   0    0   0   0   0    0   0   0   0    0   0   0   0    0   0   0   0    0   0   f   f
0000000100100011 0000000000000000 0000000000000000 0000000000000000 0000000000000000 0000000000000000 0000000000000000 0000000011111111
                                                                 * /63

```

### Convert a MAC Address into an IPv6 Local Address
```sh
 composer mac 00:FF:7A:8D:B2:4E
```

Output 

```
> Toggen\Ipv6\Runner::mac
Input: 00:FF:7A:78:92:3E
Output: fe80::2ff7:afff:e789:23e
```

### Expand a shortened IPv6 Address to full size

```sh
composer expand fe80::f692:bfff:fe84:0/48
```

```txt
> Toggen\Ipv6\Runner::expand
       Command line: fe80::f692:bfff:fe84:0/48
fe80:0000:0000:0000:f692:bfff:fe84:0000
```


Refs: [https://blog.apnic.net/2018/08/10/how-to-calculating-ipv6-subnets-outside-the-nibble-boundary/](https://blog.apnic.net/2018/08/10/how-to-calculating-ipv6-subnets-outside-the-nibble-boundary/)

