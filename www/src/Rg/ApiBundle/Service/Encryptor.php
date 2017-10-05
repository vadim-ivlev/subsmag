<?php

namespace Rg\ApiBundle\Service;

class Encryptor
{
    const CIPHER = 'AES-128-CBC';
    const IV = 'ABC^DEF_GHI+JKL-';
    const PASS = 'try_to_decrypt_it';

    public function encryptOrderId(int $id)
    {
        return base64_encode(openssl_encrypt($id, self::CIPHER, self::PASS, 0, self::IV));
    }

    public function decryptOrderId(string $key)
    {
        return openssl_decrypt(base64_decode($key), self::CIPHER, self::PASS, 0, self::IV);
    }
}
