<?php

/**
* Key handler
*/
class Key
{
	protected $specialKeywords = array("ALL");
	public $key;
	public $acceptedValues;
	public $route;
	
	public function __construct($key, $acceptedValues, $route)
	{
		$this->key = $key;
		$this->acceptedValues = $acceptedValues;
		$this->route = $route;
	}

	public function CheckacceptedValues()
	{
		if(!isset($this->key) || !isset($this->route)){return False;}

		$route = $this->getRoute();
		
		if(array_key_exists($this->key, $route) && in_array($route[$this->key], $this->acceptedValues)){
			return True;
		}else{
			return False;
		}
	}

	public function __toString(){
		return $this->key;
	}

	public function Value(){
		return $this->getRoute()[$this->key];
	}

	protected function getRoute(){
		switch ($this->route) {
			case 'GET':
				return $_GET;
				break;

				case 'POST':
				return $_POST;
				break;
		}
	}
}

/**
* Manages keys, validates, authenticates
*/
class Jitro
{
	protected static $keys = array();

	protected static $validatedKeys = NULL;
	protected static $ignoredKeys = NULL;
	
	protected function __construct(){}

	public static function AddKey($key, $acceptedValues, $route="POST")
	{
		Jitro::$keys[] = new Key($key, $acceptedValues, $route);
	}

	public static function Get($key){
		if(!isset(Jitro::$validatedKeys) || !isset(Jitro::$ignoredKeys) ){
			Jitro::$validatedKeys = Jitro::GetValidatedKeys();
			Jitro::$ignoredKeys = Jitro::GetIgnoredKeys();
		}

		if(isset(Jitro::$validatedKeys[$key])){
			return Jitro::$validatedKeys[$key]->Value();
		}else{
			throw new JitroException("No valid key: $key", 1);
		}
	}

	public static function GetValidatedKeys()
	{
		$validated = array();
		foreach (Jitro::$keys as $key) {
			if($key->CheckacceptedValues()){
				$validated[$key->key] = $key;
			}
		}
		return $validated;
	}

	public static function GetIgnoredKeys(){
		if(!isset(Jitro::$validatedKeys)){
			return array_diff(Jitro::$keys, Jitro::GetValidatedKeys());
		}else{
			return array_diff(Jitro::$keys, Jitro::$validatedKeys);
		}
	}


	public static function Authenticate($secret, $keys, $hashToCompare, $algo='sha1'){
		$kvPairs = array();
		foreach ($keys as $key => $value) {
			$kvPairs[$key] = Jitro::Get($key);
		}

		$hash = http_build_query($kvPairs, '', '&');
		$hash = hash_hmac($algo, $hash, $secret);

		if($hash == $hashToCompare){
			return True;
		}else{
			return False;
		}
	}
}


/**
* Exception Jitro class throws when errors
*/
class JitroException extends Exception{}

?>