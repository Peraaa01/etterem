<?php
abstract class DatabaseManager {
    //Változók
    protected $connection; //nem muszáj inicializálni
    //Konstruktor
    
    public function __construct( 
        string $host="localhost", 
        string $dbName="etterem", 
        string $dbUser="localuser",
         string $dbPass="localpass"
    )     {
        $this->connection = new PDO( "mysql:host=$host;dbname=$dbName"
        ,  $dbUser, $dbPass);

    }

    public function __destruct()     {
        
    }
        

    protected abstract function create( array $ColumnsValues) ;

    protected abstract function read( array $columns=['*'], string $conditions='');

    protected abstract function update( array $ColumnsValues, array $conditions);

    protected abstract function delete( array $conditions)  ;

}
?>