
#Jitro

Nano REST API framework for easy GET and POST parameter interaction


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
* ALL: Accepts all values

##License
The MIT License (MIT)