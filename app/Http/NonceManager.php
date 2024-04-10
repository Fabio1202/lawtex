<?php

namespace App\Http;

class NonceManager
{
    public static $nonce = null;

    public static function generateNonce()
    {
        if (self::$nonce === null) {
            self::$nonce = bin2hex(random_bytes(16));
        }

        return self::$nonce;
    }
}
