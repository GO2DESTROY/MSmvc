<?php

namespace system\helpers;

use system\MS_core;

class MS_secure extends MS_core
{
	/**
	 * @param     $input          : the value your wish to hash
	 * @param int $rounds         : the amount of rounds for the hash 15 is the default which is a lot
	 *                            we use blowfish for the hashing
	 *
	 * @return string: the hash value that we return
	 */
	public static function hash($input, $rounds = 15) {
		$salt       = "";
		$salt_chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
		for($i = 0; $i < 22; $i++) {
			$salt .= $salt_chars[array_rand($salt_chars)];
		}
		return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
	}

	/**
	 * @param $password_entered : the hash value to check against
	 * @param $password_hash    : the string to check with
	 *
	 * @return bool : match or no match return
	 */
	public static function matchHash($password_entered, $password_hash) {
		return crypt($password_entered, $password_hash) == $password_hash ? TRUE : FALSE;
	}

	/**
	 * @param $string         : the string to encrypt
	 * @param $key            : the key to use for the encryption
	 *                        we use Rijdael 256 for encryption comparable to AES
	 *
	 * @return string : the encrypted string
	 */
	public static function encrypt($string, $key) {
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, $iv);
	}

	/**
	 * @param $encrypted_string : the string to decrypt
	 * @param $key              : the key to use for the encryption
	 *                          we use Rijdael 256 for encryption comparable to AES
	 *
	 * @return string: the decrypted string
	 */
	public static function decrypt($encrypted_string, $key) {
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encrypted_string, MCRYPT_MODE_CBC, $iv);
	}
}