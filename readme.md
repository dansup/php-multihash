php-multihash
=============

> [multihash](//github.com/jbenet/multihash) implementation.


An IPFS Multihash PHP library. Still in active development, only of a few hash types are supported (sha1, sha256), we plan to support [all](https://github.com/jbenet/multihash/blob/master/hashtable.csv) hash types listed in the spec eventually.

## Install

Via Composer

``` bash
$ composer require dansup/php-multihash
```

## Usage

``` php
$multihash = new Dansup\Multihash\Factory;
echo $multihash->encode('testing', 0x11)->get();
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
