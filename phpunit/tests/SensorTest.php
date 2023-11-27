<?php

require ('app/libs/Sensor.php');
use PHPUnit\Framework\TestCase;


class SensorTest extends TestCase {

    private $instance;

    public function setUp() : void {
        $this->instance = new Sensor();
    }

    public function testaddrIp(){
        $out = $this->instance->addrIp();
        $exp = '127.0.0.1';

        $this->assertEquals($exp, $out, 'Adresy IP różnią się');
    }

    public function testIsLocal(){
        $out = $this->instance->isLocal('127.0.0.1');
        
        $this->assertTrue($out, 'Podany adres nie jest lokalny');
    }

    public function testBrowser(){
        $out = $this->instance->browser();
        $exp = 'Chrome 118';

        $this->assertEquals($exp, $out, 'Przeglądarki są różne');
    }

    public function testSystem(){
        $out = $this->instance->system();
        $exp = 'Windows 10';

        $this->assertEquals($exp, $out, 'Systemy są rózne');
    }

    public function testFingerprint(){
        $out = strlen($this->instance->genFingerprint('127.0.0.1'));
        $exp = 64;

        $this->assertEquals($exp, $out, "Długość różni się");
    }

}












?>