<?php
namespace glenn\security;

/**
 * The Hash class is used to create hashed strings using the crypt function in PHP.
 * This means hashes generated with this class can transparently be upgraded to a
 * stronger implementation later.
 */
class Hash
{
	/**
	 * Constant for the Blowfish algorithm.
	 */
	const BLOWFISH = 0;
	
	/**
	 * Constant for the SHA-256 algorithm.
	 */
	const SHA256 = 1;
	
	/**
	 * Constant for the SHA-512 algorithm.
	 */
	const SHA512 = 2;

	/* Instance variables */
	private $algorithm;
	private $string;
	private $cost;
	private $hash;
	private $salt;

	/**
	 * 
	 * @param string $string string to hash
	 * @param integer $algorithm defaults to BLOWFISH, can also be SHA256 and SHA512
	 * @param integer $cost BLOWFISH takes values 4-31 and SHA256 and SHA512 takes values 1000-999,999,999
	 */
	public function __construct($string, $algorithm = BLOWFISH, $cost = null)
	{
		$this->algorithm = $algorithm;
		$this->string = $string;
		$this->cost = $cost;
		$this->salt();
		$this->hash();
	}

	/**
	 * Generates a random salt based on the requirements of the selected algorithm and
	 * its cost and returns the string.
	 *
	 * @return string random salt
	 */
	public function salt()
	{
		if ($this->salt !== null) {
			return $this->salt;
		}

		if ($this->algorithm === 'blowfish') {
			if ($this->cost === null) {
				$this->salt = $this->saltBlowfish();
			} else {
				$this->salt = $this->saltBlowfish($this->cost);
			}
		} else if ($this->algorithm === 'sha256') {
			if ($this->cost === null) {
				$this->salt = $this->saltSha256();
			} else {
				$this->salt = $this->saltSha256($this->cost);
			}
		} else if ($this->algorithm === 'sha512') {
			if ($this->cost === null) {
				$this->salt = $this->saltSha512();
			} else {
				$this->salt = $this->saltSha512($this->cost);
			}
		}

		return $this->salt;
	}

	/**
	 * Executes the hash function and returns the value.
	 *
	 * @return string hashed string
	 */
	public function hash()
	{
		if ($this->hash === null) {
			$this->hash = crypt($this->string, $this->salt);
		}

		return $this->hash;
	}

	/**
	 *
	 * @param integer $cost
	 * @return string 
	 */
	private function saltBlowfish($cost = 4)
	{
		if ($cost < 4 || $cost > 31) {
			throw new OutOfRangeException("Cost must be an integer equal or between 4 and 31.");
		}
		if ($cost < 9) {
			$cost = '0' . $cost;
		}
		$salt = '$2a$' . $cost . '$' . $this->randomString(22);
		return $salt;
	}

	/**
	 *
	 * @param integer $cost
	 * @return string 
	 */
	private function saltSha256($cost = 5000)
	{
		if ($cost < 1000 || $cost > 999999999) {
			throw new OutOfRangeException("Cost must be an integer equal or between 1000 and 999,999,999.");
		}
		$salt = '$5$rounds=' . $cost . '$' . $this->randomString(16);
		return $salt;
	}

	/**
	 *
	 * @param integer $cost
	 * @return string 
	 */
	private function saltSha512($cost = 5000)
	{
		if ($cost < 1000 || $cost > 999999999) {
			throw new OutOfRangeException("Cost must be an integer equal or between 1000 and 999,999,999.");
		}
		$salt = '$6$rounds=' . $cost . '$' . $this->randomString(16);
		return $salt;
	}

	/**
	 * Generates a random string containing characters [a-zA-Z0-9./].
	 *
	 * @param integer $length
	 * @return string 
	 */
	private function randomString($length)
	{
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./0123456789';
		$string = '';
		while (strlen($string) < $length) {
			$string .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
		return $string;
	}

}