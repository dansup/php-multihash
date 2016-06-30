<?php

namespace Dansup\Multihash;

use StephenHill\Base58;

class Factory {

  private $digest;

  function __construct()
  {
    $this->digest = "";
  }

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

  function modes()
  {
    return [
    0x11 => 'sha1',
    0x12 => 'sha256',
    0x13 => 'sha512',
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

  public function get()
  {
    return $this->toBase58String();
  }

  public function getRaw()
  {
    return $this->digest;
  }

  public function toHexString() {
    return bin2hex($this->digest);
  }

  public function fromHexString() {
    return hex2bin($this->digest);
  }

  public function toBase58String() {
    return (new Base58())->encode($this->digest);
  }

  public function fromBase58String() {
    return (new Base58())->decode($this->digest);
  }

  public function toBase64String()
  {
    return base64_encode($this->digest);
  }

  public function fromBase64String()
  {
    return base64_decode($this->digest);
  }

// Decode a hash from the given Multihash.
  public function decode ($buf) {
    $res = unpack("Cinteger/Clength/A*digest", $buf);
    $hash['code'] = $res['integer'];
    $hash['hash_function'] = $this->codes()[$res['integer']];
    $hash['length'] = $res['length'];
    $hash['digest'] = $res['digest'];
    $this->digest = $hash;
    return $this;
  }

// Encode a hash digest along with the specified function code.
// Note: the length is derived from the length of the digest itself.
  public function encode ($digest = null, $code = null, $length = null) {
    if ($digest == null || $code == null) {
      throw new \Exception('multihash encode requires at least two args: digest, code');
    }

  // ensure it's a hashfunction code.
    $this->coerceCode($code);

    if ($length == null) {
      $length = strlen($digest);
    }

    if ($length && strlen($digest) !== $length) {
      throw new \Exception('digest length should be equal to specified length.');
    }

    if ($length > 127) {
      throw new \Exception('multihash does not yet support digest lengths greater than 127 bytes.');
    }
    $this->digest = $this->doEncode($digest, $code);
    return $this;
  }

  private function doEncode($digest, $code)
  {
    $enc = false;
    $mode = $this->modes()[$code];
    $key = $this->codes()[$code];
    $hashed = hash($mode, $digest, true);
    $length = strlen($hashed);
    $enc = pack("CCA*", $code, $length, $hashed);
    return $enc;
  }

// Converts a hashfn name into the matching code
  private function coerceCode ($name) {

    $mode = $this->modes()[$name];

    if ( is_int($name) == false) {
      throw new \Exception("Hash function code should be a number. Got: {$code}");
    }

    if ($mode == false OR $this->isAppCode($name) == false) {
      throw new \Exception("Unrecognized function code: {$name}");
    }
    return;
  }

// Checks wether a code is part of the app range
  function isAppCode($code) {
    return ($code > 0 && $code < 0x41);
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
      throw new \Exception('multihash unknown function code');
    }

    if (substr($multihash[2]) !== multihash[1]) {
      throw new \Exception('multihash length inconsistent');
    }
  }
}