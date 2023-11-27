<?php
/**
 * Klasa do weryfikacji informacji o użytkowniku
 * Sprawdza adres IP, przeglądarke i system operacyjny
 * @author Krzysztof Solarczyk
 * @version 1.0
 */
require(__DIR__.'/../../vendor/autoload.php');


class Sensor {


    /**
     * @desc Sprawdza, czy podany adres IP jest lokalny
     * @param int $addrIp Adres IP do weryfikacji
     * @return true|false	Token lokalny lub nie
     */
    public function isLocal(string $addrIp) : bool{
        $IPs = 'localhost, local, ::1';
        if(strpos($IPs, $addrIp) !== false){
            return true;
        } else if (filter_var($addrIp, FILTER_VALIDATE_IP)){
            return (!filter_var($addrIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE));
        } else return false;
    }

        /**
     * @desc Sprawdza i zwraca adres IP użytkownika
     * @param string $email Adres email użytkownika do uwierzytelnienia
     * @param int $id	Numer ID użytkownika do uwierzytelnienia
     * @return array|false	Wygenerowany Token LUB Fałsz
     */
    public function addrIp($getProxy = null){
        $getProxy ??= $_SERVER;

        if (!empty($getProxy['HTTP_X_FORWARDED_FOR'])){
            return  $getProxy["HTTP_X_FORWARDED_FOR"];  
        } else { 
            return $getProxy['REMOTE_ADDR'] ? $getProxy['REMOTE_ADDR'] : 'brak';
        }
    }

        /**
     * @desc Sprawdza i zwraca informacje o przeglądarce użytkownika
     * @return string	Informacja o przeglądarce lub o braku danych
     */
    public function browser() : string{
        $info = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);

        return $info->browser->toString() ? $info->browser->toString() : 'brak';
    }

        /**
     * @desc Sprawdza i zwraca informacje o systemie operacyjnym użytkownika
     * @return string	Informacja o systemie lub o braku danych
     */
    public function system() : string{   
        $info = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);

        return $info->os->toString() ? $info->os->toString() : 'brak';
    }


        /**
     * @desc Generuje kod odcisku palca
     * @param int $IP Adres IP użytkownika
     * @param string $algo Rodzaj algorytmu hashującego
     * @return string	Wygenerowany kod odcisku palca
     */
    public function genFingerprint($IP, $algo = 'sha512'){

       return hash_hmac($algo, $_SERVER['HTTP_USER_AGENT'], hash($algo, $IP), true);
    }
}
















?>