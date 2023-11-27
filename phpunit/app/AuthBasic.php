<?php
require('app/libs/DataBaseConn.php');
require('app/libs/Sensor.php');

/**
 * Klasa do autoryzacji jednorazowego dostępu do fragmentu serwisu
 * @author Grzegorz Petri
 * @version 2.0
 */
class AuthBasic {
	/**
	 * @desc Generuje kod wymagany do podania podczas autoryzacji dostępu, wg. podanych parametrów
	 * @param int $length Długość kodu - liczba znaków
	 * @param int $min Minimalna wartość dla generowanego numeru
	 * @param int $max Maksymalna wartość dla generowanego numeru
	 * @return int Zwraca wygenerowaną na podstawie parametrów liczbę, która musi zostać uzupełniana zerami, jeżeli trzeba spełnić długość
	 */
	public function createCode(	$length=6, $min=1, $max=999999 ){
		$max = substr($max,0,$length);
		return str_pad(mt_rand($min,$max),$length,'0',STR_PAD_LEFT);// losowanie 1-999999
	}


		/**
	 * @desc Porównuje kod przesłany przez Użytkownika z kodem zapisanym dla niego w bazie danych
	 * @param string $emlAuth	Adres email użytkownika przesyłającego request
	 * @param int $idzAuth	Numer ID użytkownika przesyłającego request
	 * @param string $authCode 	Kod przesłany przez użytkownika
	 * @return true|false	Dane są takie same lub różne
	 * 
	 * @author Krzysztof Solarczyk
	 */
	public function compAuthCode( $emlAuth, $idzAuth, $authCode ){

		$conn = new DataBaseConn('localhost', 'root', '', 'unittest');
		$opt = array("authCode = '$authCode'");
		$dbInf = $conn->get('cmsWebsiteAuth', 'authCode', $opt);
		if($dbInf){
			return true;
		} return false;
	}
	public function doAuthByEmail( $person, $email ){}
	public function checkIfValidReqest( $person, $email ){}
	private function checkIfValidReqest2f( $emlAuth, $idzAuth ){}
	public function verifyQuickRegCode($codeNo){}
	/**
	 * @desc Tworzy wpis w BD z numerem pozwalającym na uwierzytelnienie Requesta
	 * Tworzony Token do uwierzytelnienia zapisując adres Email oraz ID użytkownika
	 * Token musi zostać wysłany na pocztę użytkownika, stąd zwracany jest Obiekt informacyjny
	 * @param string $email Adres email użytkownika do uwierzytelnienia
	 * @param int $id	Numer ID użytkownika do uwierzytelnienia
	 * @return array|false	Wygenerowany Token LUB Fałsz
	 * 
	 * @version 2.0
	 * @author Krzysztof Solarczyk
	 */
	public function createAuthToken( $email, $id){
		$sensor = new Sensor();
		$authCode = $this->createCode();
		$authDate = date("Y-m-d");
		$authHours = date("H:i:s");
		$addrIp = $sensor->addrIp();
		$opSys = $sensor->system();
		$browser = $sensor->browser();

		$cont = array(
			'emlAuth'=>$email,'authCode'=>$authCode,
			'authDate'=>$authDate,'authHour'=>$authHours,
			'addrIp'=>$addrIp,'reqOs'=>$opSys,'reqBrw'=>$browser
			);
		$contDb = serialize($cont);

		$db = new DataBaseConn('localhost', 'root', '', 'unittest');
		$fingerprint = $sensor->genFingerprint($addrIp);
		$fingerprint = str_replace("'", ",", $fingerprint );
		$dt = new DateTime($authDate.' '.$authHours);
		$dt = $dt->format('Y-m-d H:i:s');
		$tbl = 'cmsWebsiteAuth';
		$db->clearVals($tbl);
		$cols = 'session_id, usrId, addrIp, fingerprint, dateTime, content, email, authCode';
		$vals = "'1234567890','$id','$addrIp','$fingerprint','$dt','$contDb','$email','$authCode'";
		$opt = array(
			"usrId = $id",
			"authCode IS NOT NULL"
		);
		$db->put($tbl, $cols, $vals);

		$fData = $db->get($tbl, 'content', $opt);
		$tok = (unserialize($fData)==$cont) ? 0 : 'err:1045';
		$resp = ($tok===0) ? $cont : false;
		return $resp;

		
	}
/*
 * */
}