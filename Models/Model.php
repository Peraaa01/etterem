<?php
require_once dirname(__DIR__) . '/helpers/init.php';
require_once(sprintf('%s/%s', HELPERS_DIR, 'helpers.php')); //relavív útvonal
require_once(sprintf('%s/%s', HELPERS_DIR, 'DatabaseManager.php')); //relavív útvonal

class Model extends DatabaseManager
{

    protected array $casts;
    protected string $tableName;

    public function __construct(int|null $id = null)
    {
        parent::__construct();

       
        if (is_int($id)) {
            $result=$this->read(['*'], "id=$id");

            $data=reset($result);

            foreach ($data as $key => $value) {
                $this->{$key }= $value;
            }
        }

    }
    public function create(array $ColumnsValues)
    {


        /* konkrét adat beszúrása 
     
            $stmt=$this->connection->prepare("insert into users (email, username, fullname, country_and_city, address, password ) VALUES (:email, :username, :fullname, :country_and_city, :address, :password)");
             return $stmt->execute([
                 'email' => 'teszt_adatkapcs@lil.hu',
                 'username' => 'teszt_adat_kapcsolat',
                 'fullname' => 'Teszt',
                 'country_and_city' => 'Budapest',
                 'address' => 'kis utca 8.',
                 'password' => ''
             ]
             ); */


        //általános esetben
        $columnNames = implode(',', array_keys($ColumnsValues));
     
        $columnNamesPrepared = ':' . implode(',:', array_keys($ColumnsValues));

        $stmt = $this->connection->prepare("insert into $this->tableName ($columnNames) VALUES ($columnNamesPrepared)");

        $result = $stmt->execute($ColumnsValues);
        if ($result) {
            return $this->connection->lastInsertId();
        }
        return false;
    }


    protected function read(array $columns = ['*'], string $conditions = '')
    {
        $columnNames = implode(',', $columns);
        if ($conditions != '') {
            $conditions = "where $conditions";
        }

        $stmt = $this->connection->query("select $columnNames from $this->tableName  $conditions");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function read_two_table( array $columns = ['*'], string $conditions = '',string $order = '',  string $otherTableName, array $onColumns)
    {   
        $columnNames = implode(',', $columns);
        if ($conditions != '') {
            $conditions = "where $conditions";
        }
    
        $stmt = $this->connection->query("select $columnNames from $this->tableName inner join $otherTableName  ON $onColumns[0] = $onColumns[1] $conditions $order");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function update(array $ColumnsValues, array $conditions) 
    {

        $set = "set ";
        $i = 0;
        foreach ($ColumnsValues as $columnName => $value) {
            $set .= "$columnName = '$value'";
            if ($i < count($ColumnsValues) - 1)
                $set .= ", ";
            $i++;
        }

        $conditionString = $this->assembleConditions($conditions);

        // összeszerkesztettem a query-t, behelyettesítés nem kell ->nem kell prepare()

 /*       $stmt = $this->connection->query("update $this->tableName $set $conditionString");
        return $stmt->execute(); */

                //általános esetben
                // $columnNames = implode(',', array_keys($ColumnsValues));
     
                // $columnNamesPrepared = ':' . implode(',:', array_keys($ColumnsValues));
        

                $set2 = "set ";
                $i = 0;
                foreach ($ColumnsValues as $columnName => $value) {
                    $set2 .= "$columnName = :$columnName";
                    if ($i < count($ColumnsValues) - 1)
                        $set2 .= ", ";
                    $i++;
                }
        
                //dd("update $this->tableName $set2  $conditionString");
                $stmt = $this->connection->prepare("update $this->tableName $set2  $conditionString");
        
                $result = $stmt->execute($ColumnsValues);
                return $result;
    }

    protected function delete(array $conditions) :bool
    {
        $conditionString = $this->assembleConditions($conditions);


        try {
            $stmt = $this->connection->query("delete from $this->tableName $conditionString");

            return $stmt->execute();
    
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                error_log($e->getMessage());
                echo "Nem sikerült törölni a rekordot, mert más rekordok hivatkoznak rá.";
            } else {
                // Egyéb hiba
                error_log($e->getMessage());
                echo "Hiba történt: " . $e->getMessage();
            }
            return false;
        }


    }

    protected function assembleConditions(array $conditions)
    {
        $conditionString = "where ";
        $i = 0;
        foreach ($conditions as $columnName => $value) {
            $conditionString .= " $columnName = '$value'";
            if ($i < count($conditions) - 1)
                $conditionString .= " and ";
            $i++;
        }
        return $conditionString;
    }

 

    public function slug($text){
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
    
        // trim
        $text = trim($text);
    
        // remove duplicated - symbols
        $text = preg_replace('~-+~', '-', $text);
    
        // lowercase
        $text = strtolower($text);

        // replace space with _
        $text = str_replace(' ', '_', $text);
        if (empty($text)) {
          return 'n-a';
        }
    
        return $text;
    }
    

    protected function castValues(array $data): array
    {
        foreach ($this->casts as $key => $cast) {
            if (array_key_exists($key, $data)) {
                if ($cast === 'encrypt') {
                    $data[$key] = password_hash($data[$key], PASSWORD_BCRYPT, ['cost' => 12]);
                }
            }
        }
        return $data;
    }
}
