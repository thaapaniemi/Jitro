<?php

/**
* Key handler
*/
class Key
{
	/**
	* Keywords
	* @var array
	*/
	protected static $specialKeywords = array("ALL");
	
	/**
	* Key
	* @var string
	*/
	public $key;

	/**
	* Accepted values for keys
	* @var array
	*/
	public $acceptedValues;

	/**
	* Route to get key GET/POST/SESSION/REQUEST/COOKIE/FILES
	* @var string
	*/
	public $route;
	
	/**
	* Create key
	* @param string $key Key
	* @param array(string) $acceptedValues Accepted values for key
	* @param string $route GET or POST
	*/
	public function __construct($key, $acceptedValues, $route)
	{
		$this->key = $key;
		$this->acceptedValues = $acceptedValues;
		$this->route = $route;
	}

	/**
	* Check if Key is valid(found, result matches accepted values)
	* @return Boolean Is valid
	*/
	public function CheckacceptedValues()
	{
		if(!isset($this->key) || !isset($this->route)){return False;}
		$route = $this->getRoute();
		
		if(array_key_exists($this->key, $route)){
			if(in_array($route[$this->key], $this->acceptedValues)){ return True; }
			
			foreach (Key::$specialKeywords as $key => $value) {
				if(in_array($value, $this->acceptedValues)){return True;}
			}
		}

		return False;
	}

	public function __toString(){
		return $this->Key();
	}

	public function Key(){
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

			case 'SESSION':
			return $_SESSION;
			break;

			case 'COOKIES':
			return $_COOKIE;
			break;

			case 'REQUEST':
			return $_REQUEST;
			break;

			case 'FILES':
			return $FILES;
			break;
		}
	}
}

/**
* Manages keys, validates, authenticates
*/
class Jitro
{
	/**
	* Keys added by AddKey-method
	*/
	protected static $keys = array();

	/**
	* Validated keys from keys-array
	*/
	protected static $validatedKeys = NULL;

	/**
	* Keys that didn't validate
	*/
	protected static $ignoredKeys = NULL;
	
	/**
	* Static class only
	*/
	protected function __construct(){}

	/**
	* Add new key to validation
	* @param string $key Key
	* @param array(string) $acceptedValues Accepted values for key
	* @param string $route GET or POST or etc
	*/
	public static function AddKey($key, $acceptedValues, $route="POST")
	{
		Jitro::$keys[] = new Key($key, $acceptedValues, $route);
	}

	/**
	* Get value of key or die trying
	* @param string $key Key
	* @return string Value
	* @throws JitroException No valid key
	*/
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

	/**
	* Validate keys
	* @return array Validated keys
	*/
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

	/**
	* Get complement of Validated keys
	* @return array Ignored keys
	*/
	public static function GetIgnoredKeys(){
		if(!isset(Jitro::$validatedKeys)){
			return array_diff(Jitro::$keys, Jitro::GetValidatedKeys());
		}else{
			return array_diff(Jitro::$keys, Jitro::$validatedKeys);
		}
	}

	/**
	* Authenticate Route parameters
	* @param string $secret Secret
	* @param array(string) $keys Keys included in hash
	* @param string $hashToCompare Result is compared against this hash
	* @param string $algo Used hashing algorithm
	* @return Boolean Is authenticated?
	*/
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

	/**
	* Test if key/keys are valid
	* @param string all keys wanted to test
	* @return Boolean is keys valid
	*/
	static public function IsValid(){
		try{
			foreach (func_get_args() as $value) {
				Jitro::Get($value);
			}
			
			return True;
		}catch(JitroException $e){
			return False;
		}
	}
}


/**
* Exception Jitro class throws when errors
*/
class JitroException extends Exception{}

?>