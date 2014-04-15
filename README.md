[![Build Status](https://travis-ci.org/thaapaniemi/Jitro.svg?branch=master)](https://travis-ci.org/thaapaniemi/Jitro)
#Jitro
la jitros jitro jalge REST

Tiny framework for easy PHP REST parameter interaction

##Arrays
* GET, POST, REQUEST, COOKIE, SESSION, FILES

##Usage
	require_once('jitro.php');
	try{
		Jitro::AddKey('request',['add','remove'], 'POST');
		$req = Jitro::Get('request');

		if($req == 'add'){...}
	}catch(JitroException $e){
		// Handle missing keys
	}

##Special keywords
* ALL: Accepts all values (must exists)
* NOTEMPTY: Accepts all nonempty values

##License
The MIT License (MIT)
