<?php
/**
 * Klasa do utworzenia połączenia i operacji na bazie danych
 * @author Krzysztof Solarczyk
 * @version 1.0
 */

use SebastianBergmann\Environment\Console;

class DataBaseConn {
    
    private $host;
    private $user;
    private $pass;
    private $database;

    
    public function __construct(string $host, string $user, string $pass, string $database){
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;

    }

        /**
     * @desc Dokonuje przesyłu danych do bazy
     * @param string $table Nazwa tabeli do której mają trafić dane
     * @param string $columns Kolumny do których mają trafić dane
     * @param string $values Dane, które mają trafić do bazy danych
     */
    public function put(string $table, ?string $columns, ?string $values ){
        
        $conn = mysqli_connect($this->host, $this->user, $this->pass, $this->database);
        $sql = "INSERT INTO `$table` ($columns) VALUES ($values);";
        $results = mysqli_query($conn, $sql);
    }

        /**
     * @desc Wysyła zapytanie do bazy danych
     * @param string $table Tabela z której mają zostać wyświetlone dane
     * @param string $columns Kolumna, z której mają zostać wyświetlone dane
     * @param array $options Zbiór klauzuli filtrujących dane
     * @return string   Zwrócenie danych
     */
    public function get(string $table, ?string $columns, array $options){
        $options = implode(" AND ", $options);
        $conn = mysqli_connect($this->host, $this->user, $this->pass, $this->database);
        $sql = "SELECT $columns FROM $table WHERE $options;";
        $results = mysqli_query($conn, $sql);
        $res_arr = mysqli_fetch_assoc($results);
        if(is_array($res_arr)){
            $values = implode(", ", $res_arr);
            mysqli_free_result($results);
        } else $values = $res_arr;
        
        return $values;
    }

    /**
     * @desc Czyści dane zawarte w tabeli
     * @param string $table Tabela z której mają zostać usunięte dane
     */
    public function clearVals(string $table){
        $sql = "TRUNCATE $table";
        $conn = mysqli_connect($this->host, $this->user, $this->pass, $this->database);
        mysqli_query($conn, $sql);
    }
}
?>