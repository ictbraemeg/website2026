<?php
// 32‑byte key, generated once and stored outside webroot or in env
// e.g. base64_decode(getenv('BRAEMEG_ENC_KEY'))
const BRAEMEG_ENC_KEY = "32-byte-random-binary-key-here";

function enc(string $plaintext): string
{
    if ($plaintext === "") {
        return "";
    }

    $key = BRAEMEG_ENC_KEY;
    $method = "aes-256-gcm";
    $iv = random_bytes(openssl_cipher_iv_length($method));

    $ciphertext = openssl_encrypt(
        $plaintext,
        $method,
        $key,
        OPENSSL_RAW_DATA,
        $iv,
        $tag,
    );

    // Store iv + tag + ciphertext (base64)
    return base64_encode($iv . $tag . $ciphertext);
}

function dec(?string $encoded): string
{
    if (!$encoded) {
        return "";
    }

    $data = base64_decode($encoded, true);
    if ($data === false) {
        return "";
    }

    $method = "aes-256-gcm";
    $ivLen = openssl_cipher_iv_length($method);

    $iv = substr($data, 0, $ivLen);
    $tag = substr($data, $ivLen, 16); // GCM tag is 16 bytes
    $ct = substr($data, $ivLen + 16);

    $key = BRAEMEG_ENC_KEY;

    $pt = openssl_decrypt($ct, $method, $key, OPENSSL_RAW_DATA, $iv, $tag);

    return $pt === false ? "" : $pt;
}
