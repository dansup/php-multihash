php-multihash
=============

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

> [multihash](//github.com/jbenet/multihash) implementation.


An IPFS Multihash PHP library. **Still in active development**, only of a few hash types are supported (sha1, sha256), we plan to support [all](https://github.com/jbenet/multihash/blob/master/hashtable.csv) hash types listed in the spec eventually.

## Install

Via Composer

``` bash
$ composer require dansup/php-multihash
```

## Usage

``` php
$multihash = new Dansup\Multihash\Factory;
echo $multihash->encode('testing', 0x12)->get();
=> "QmcJf1w9bVpquGdzCp86pX4K21Zcn7bJBUtrBP1cr2NFuR"
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/dansup/php-multihash.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/dansup/php-multihash/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/dansup/php-multihash.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/dansup/php-multihash.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/dansup/php-multihash.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/dansup/php-multihash
[link-travis]: https://travis-ci.org/dansup/php-multihash
[link-scrutinizer]: https://scrutinizer-ci.com/g/dansup/php-multihash/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/dansup/php-multihash
[link-downloads]: https://packagist.org/packages/dansup/php-multihash
[link-author]: https://github.com/dansup
[link-contributors]: ../../contributors