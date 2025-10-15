<?php

/**
 * Created by PhpStorm.
 * User: Nan
 * Date: 2021/5/11
 * Time: 11:36
 */
namespace crypt;
class PhpEncrypter
{
    public function encrypt(string $text, string $password) {
        /* The function to encrypt a plain utf-8 encoded string to a cipher format using a password. The function will return a encrypted form of string, so we need to catch that returning value to a variable. We will use this function to encrypt the text requested by the user. */

        $key = 0;
        $n  = 0;
        for ($i = 0; $i < strlen($password); $i++) {
            // Iterating through each character of the password string to generate a key

            if ($n % 2 == 0) {
                $key += ord($password[$i]);
            } else {
                $key -= ord($password[$i]);
            }
            $n += 1;
        }
        // Making the encryption key possitive if its negative
        if ($key < 0) {$key = $key * (-1);}
        $key += strlen($password);

        // Encrypting the user specified string text
        $encryptedText = '';
        for ($i = 0; $i < strlen($text); $i++) {
            // Iterating through each characters of the string text in order to convert them to cipher format

            $encryptedText[$i] = chr((ord($text[$i]) + $key) % 256);
        }

        // Encoding the cipher text into the base64 format
        $encryptedText = base64_encode($encryptedText);
        return $encryptedText;
    }

    public function decrypt(string $text, string $password) {
        /* The function to decrypt a cipher format text to the original plain utf-8 encoded string. The function returns the plain string, so to catch the returning string we need to store the value to a variable right after we call this function. The function will be used in the decryption of the user requested text. Note that the function will succesfully only those strings which are encrypted using the above function, and also you would need the original password. */

        // Decoding the cipher text from base64 format back to the utf-8 encoding
        $text = base64_decode($text);

        $key = 0;
        $n  = 0;
        for ($i = 0; $i < strlen($password); $i++) {
            // Iterating through each character of the password string to generate a key

            if ($n % 2 == 0) {
                $key += ord($password[$i]);
            } else {
                $key -= ord($password[$i]);
            }
            $n += 1;
        }
        // Making the encryption key possitive if its negative
        if ($key < 0) {$key = $key * (-1);}
        $key += strlen($password);

        // Converting the cipher text back to the plain format text
        $decryptedText = '';
        for ($i = 0; $i < strlen($text); $i++) {
            // Iterating through each characters of the string text in order to convert them to cipher format

            $decryptedText[$i] = chr((ord($text[$i]) - $key) % 256);
        }
        return $decryptedText;
    }
}