
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

##License
The MIT License (MIT)


###Figure this

Super simple Generic REST API framework
Jitro? x1 has control over/harnesses/manages/directs/conducts x2 in x3 (activity/event/performance).
Gunka? x1 [person] labors/works on/at x2 [activity] with
Vlipa? x1 has the power to bring about x2 under conditions x3;
Macnu? x1 (event/action/process) is manual [not automatic] in function
Zmiku? x1 is automatic in function x2 under conditions x3.

-Define wanted parameters by wanted Route(GET/POST)
-define output
