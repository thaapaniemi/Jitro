<?php

/**
* Key handler
*/
class Key
{
	public $key;
	public $parameters;
	public $route;
	
	public function __construct($key, $parameters, $route)
	{
		$this->key = $key;
		$this->parameters = $parameters;
		$this->route = $route;
	}

	public function CheckParameters()
	{
		if(!isset($this->key) || !isset($this->route)){return False;}

		$route = NULL;
		switch ($this->route) {
			case 'GET':
				$route = $_GET;
				break;

				case 'POST':
				$route = $_POST;
				break;
			
			default:
				# code...
				break;
		}

		foreach ($this->parameters as $value) {
			if(!isset($route[$value])){return False;}
		}

		return True;
	}
}

/**
* Manages keys, maps and DB?
*/
class Jitro
{
	private static $keys;
	private static $map;
	
	private function __construct(){}

	public static function AddKey($key, $parameters, $route="POST")
	{
		$keys[] = new Key($key, $parameters, $route);
	}

	public static function GetValidatedKeys()
	{
		$validated = array();
		foreach ($this->keys as $key) {
			if($key->CheckParameters()){
				$validated[] = $key;
			}
		}

		return $validated;
	}

	public static GetIgnoredKeys(){
		return array_diff($this->keys, $this->GetValidatedKeys());
	}

	public static AddMap($key, $map){
		$this->map[$key] = $map;
	}

	public static GetDataFromDB($keyObj, $callback){
		
		$mkey = $keyObj->key;
		if(isset($this->map[$mkey])){$mkey=$this->map[$mkey];}

		$parameters = array();
		foreach ($key->parameters as $key => $value) {
			if(isset($this->map[$key])){$parameters[ $this->map[$key] ]=$value;}
			else{$parameters[$key]=$value}
		}

		//TODO: Read params to call
		// make callback with result as param.

	}


}


?>