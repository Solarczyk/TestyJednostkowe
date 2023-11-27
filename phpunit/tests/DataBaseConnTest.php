<?php

require('app/libs/DataBaseConn.php');


use PHPUnit\Framework\TestCase;

class DataBaseConnTest extends TestCase {

    private $instance;

    public function setUp() : void {
        $this->instance = new DataBaseConn('localhost', 'root', '', 'unittest');
        $this->instance->clearVals('cmsWebsiteAuth');
    }

    public function testQuery(){
        $db = 'cmsWebsiteAuth';
        $cols = 'addrIp, session_id';
        $vals = "'127.0.0.1', '123456'";
        $opt = array(
			"addrIp = '127.0.0.1'",
			"session_id IS NOT NULL"
		);
        $this->instance->put($db, $cols, $vals);
        $res = $this->instance->get($db, $cols, $opt);
        $exp = "127.0.0.1, 123456";

        $this->assertEquals($exp,$res,'Dane nie zalaczone');

    }
    public function tearDown() : void {
        unset($this->instance);
    }


}



?>