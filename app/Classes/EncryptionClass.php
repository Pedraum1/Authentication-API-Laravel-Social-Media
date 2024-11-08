<?php

namespace App\Classes;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Support\Facades\Crypt;

class EncryptionClass{

  public static function encryptId(int $id): string|null
  {
      try {
          $id = Crypt::encrypt($id);
      } catch (EncryptException $e) {
          return null;
      }
      return $id;
  }

  public static function decryptId(string $id): int|null
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return null;
        }
        return $id;
    }
}