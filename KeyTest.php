<?php
require 'jitro.php';

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-04-07 at 06:41:41.
 */
class KeyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Key
     */
    #protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        #$this->object = new Key("aa",);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    protected function emptyArrays(){
    	$_GET = array();
    	$_POST = array();
    	$_REQUES = array();
    	$_COOKIE = array();
    	$_SESSION = array();
    }

    protected function setArray($target, $newArray){
    	switch ($target) {
    		case 'GET':
    			$_GET = $newArray;
    			break;
    		case 'POST':
    			$_POST = $newArray;
    			break;
    		case 'REQUEST':
    			$_REQUEST = $newArray;
    			break;
    		case 'SESSION':
    			$_SESSION = $newArray;
    			break;
    		case 'COOKIE':
    			$_COOKIE = $newArray;
    			break;
    		
    		default:
    			throw new Exception("Error Processing Request", 1);
    			break;
    	}
    }

    protected function tArray($target){
    	$this->setArray($target,array("a"=>"1", "b"=>"2", "c"=>NULL, "x"=>"3"));
    	
    	$okKeys = array();
    	#OK keys
    	$keys[] = new Key("a", "ALLKEYS", $target);
    	$keys[] = new Key("b", "NOTEMPTY", $target);
    	$keys[] = new Key("x", "3", $target);

    	$errorKeys = array();
    	$keys[] = new Key("g", "ALLKEYS", $target);
    	$keys[] = new Key("c", "NOTEMPTY", $target);
    	$keys[] = new Key("x", "5", $target);


    	foreach ($okKeys as $key => $value) {
    		$this->assertTrue($value->CheckacceptedValues());
    	}

    	foreach ($errorKeys as $key => $value) {
    		$this->assertFalse($value->CheckacceptedValues());
    	}

    }

	/**
     * @covers Key::CheckacceptedValues
     */
    public function testArrays(){
    	$targets = array("GET","POST","REQUEST","SESSION","COOKIE");

    	foreach ($targets as $key => $value) {
    		$this->emptyArrays();
    		$this->tArray($value);
    	}

    }

    /**
     * @covers Key::__toString
     */
    public function test__toString()
    {
        $key = new Key("aa","ALL","GET");

        $this->assertEquals($key, 'aa');
    }

    /**
     * @covers Key::Value
     */
    public function testValue()
    {
        $_GET['aa'] = 'x';
        $_POST['bb'] = 'y';

        $keyGET  = new Key("aa", array("aa"), "GET");
        $keyPOST = new Key("bb", array("aa"), "POST");

        $this->assertEquals($keyGET->Value(), 'x');
        $this->assertEquals($keyPOST->Value(), 'y');
    }
}
