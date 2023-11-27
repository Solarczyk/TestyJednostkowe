<?php
require('app/AuthBasic.php');
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;

class AuthBasicTest extends TestCase {
    private $instance;

    public function setUp() : void {
        $this->instance = new AuthBasic();
    }
    public function testCreateAuthToken(){
            $exp = array(
              'emlAuth'=>'krzysztof.solarczyk@zsegw.pl','authCode'=>'131313',
              'authDate'=>date("Y-m-d"),'authHour'=>date("H:i:s"),
              'addrIp'=>'127.0.0.1','reqOs'=>'Windows 10','reqBrw'=>'Chrome 118'
            );
            $out = $this->instance->createAuthToken('krzysztof.solarczyk@zsegw.pl',13);
            $out['authCode'] = '131313';
            $this->assertEquals($exp,$out,'Tablice są różne');
        }

        public function testCreateCode(){
            $out = $this->instance->createCode();
            fwrite(STDERR, print_r($out, true));
            $len = strlen($out);
            $this->assertIsNumeric($out,'Wylosowano: '.$out);
            $this->assertEquals(6,$len,'Długość: '.$len);
        
            $out = $this->instance->createCode(4);
            $len = strlen($out);
            $this->assertIsNumeric($out,'Wylosowano: '.$out);
            $this->assertEquals(4,$len,'Długość: '.$len);
            $out = str_pad(1111,6,'0',STR_PAD_LEFT);
            $len = strlen($out);
            $this->assertIsNumeric($out,'Wylosowano: '.$out);
            $this->assertEquals(6,$len,'Długość: '.$len);
        }

        public function testCompAuthCode(){
            $email = 'krzysztof.solarczyk@zsegw.pl';
            $id = 13;
            $resp = $this->instance->createAuthToken($email, $id);

            $out = $this->instance->compAuthCode($email, $id, $resp['authCode']);
            assertTrue($out, "Dane przesłane przez użytkownika różnią się od danych w bazie");
            
        }
        public function tearDown() : void {
            unset($this->instance);
        }
}