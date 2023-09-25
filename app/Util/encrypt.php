<?php

namespace App\Util;

class encrypt {

    private static $key  = "WGgyNEt0cGRvNDVDQnQy@";  
    private static $pass = "SGFDQDIwMjNyZFMu==";//Encriptamos en base 64 una frase
    private $iv;

    const METHOD = 'aes-256-cbc';

    function __construct() {
        $this->iv = chr(0x15) . chr(0x28) . chr(0x32) . chr(0x63) . chr(0x20) . chr(0x15) . chr(0x123) . chr(0x85) . chr(0x74) . chr(0x14) . chr(0x2) . chr(0x65) . chr(0x3) . chr(0x0) . chr(0x6) . chr(0x5);
    }

    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public function encrypted(string $txt_plant): string {
        return $this->base64url_encode(openssl_encrypt($txt_plant, self::METHOD, substr(hash('sha256', self::$pass, true), 0, 32), OPENSSL_RAW_DATA, $this->iv));
    }

    public function decrypted($txt_plant): string {
        if (is_null($txt_plant)) {
            throw new Exception('El texto se encuentra vacío');
        }
        return openssl_decrypt(
            $this->base64url_decode($txt_plant), self::METHOD, substr(hash('sha256', self::$pass, true), 0, 32), OPENSSL_RAW_DATA, $this->iv);
    }
}