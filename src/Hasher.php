<?php

namespace Dansup\Multihash;

use \StephenHill\Base58;

class Hasher {

  function names() {
    return [
    'sha1' => 0x11,
    'sha2-256' => 0x12,
    'sha2-512' => 0x13,
    'sha3' => 0x14,
    'blake2b' => 0x40,
    'blake2s' => 0x41
    ];
  }

  function codes()
  {
    return [
    0x11 => 'sha1',
    0x12 => 'sha2-256',
    0x13 => 'sha2-512',
    0x14 => 'sha3',
    0x40 => 'blake2b',
    0x41 => 'blake2s'
    ];
  }

  function defaultLengths() {
    return [
    0x11 => 20,
    0x12 => 32,
    0x13 => 64,
    0x14 => 64,
    0x40 => 64,
    0x41 => 32
    ];
  }

  function toHexString ($m) {
    return bin2hex($m);
  }

  function fromHexString ($s) {
    return hex2bin($s);
  }

  function toB58String ($m) {
    return (new Base58())->encode($m);
  }

  function fromB58String ($s) {
    return (new Base58())->decode($s);
  }

// Decode a hash from the given Multihash.
  function decode ($buf) {

    return [
    'code'  => $buf[0],
    'name'  =>  $this->codes()[$buf[0]],
    'length'  => strlen($buf[1]),
    'digest'  => $buf[2]
    ];
  }

// Encode a hash digest along with the specified function code.
// Note: the length is derived from the length of the digest itself.
  function encode ($digest = null, $code = null, $length = null) {
    if ($digest == null || $code == null) {
      throw new \Exception('multihash encode requires at least two args: digest, code');
    }

  // ensure it's a hashfunction code.
  //$this->coerceCode($code);

    if ($length == null) {
      $length = strlen($digest);
    }

    if ($length && strlen($digest) !== $length) {
      throw new \Exception('digest length should be equal to specified length.');
    }

    if ($length > 127) {
      throw new \Exception('multihash does not yet support digest lengths greater than 127 bytes.');
    }
    $encoded = $this->doEncode($digest, $code);
    return $encoded;
  }

  function doEncode($digest, $code)
  {
    $enc = false;
    switch ($digest) {
      case 'sha1':
      $enc = hash('sha1', $code);
      break;
      case 'sha2-256':
      $enc = hash('sha256', $code);
      break;
      case 'sha2-512':
      $enc = hash('sha512', $code);
      break;
      
    }
    if($enc == false) {
      throw new \Exception('invalid digest');
    }
    return $enc;
  }

// Converts a hashfn name into the matching code
  function coerceCode ($name) {
    $code = $name;

    $code = $this->names()[$name];

    if ( is_int($code) == false) {
      throw new \Exception("Hash function code should be a number. Got: {$code}");
    }

    if ($this->codes()[$code] == false && $this->isAppCode($code) == false) {
      throw new \Exception("Unrecognized function code: {$code}");
    }

    return $code;
  }

// Checks wether a code is part of the app range
  function appCode ($code) {
    return ($code > 0 && $code < 0x10);
  }

// Checks whether a multihash code is valid.
  function validCode ($code) {
    if ($this->isAppCode($code) == true) {
      return true;
    }

    if ($this->codes()[$code] == true) {
      return true;
    }

    return false;
  }

  function validate ($multihash) {

    if ( strlen($multihash) < 3) {
      throw new \Exception('multihash too short. must be > 3 bytes.');
    }

    if ( strlen($multihash) > 129) {
      throw new \Exception('multihash too long. must be < 129 bytes.');
    }

    $code = $multihash[0];

    if ($this->isValidCode($code) === false) {
      throw new \Exception(`multihash unknown function code: 0x${code.toString(16)}`);
    }

    if (substr($multihash[2]) !== multihash[1]) {
      throw new \Exception(`multihash length inconsistent: 0x${multihash.toString('hex')}`);
    }
  }
}