<?php 

    namespace App\Database;
    use PDO;
    use Exception;
use PDOException;

class Connect{
    private $host;
    private $username;
    private $password;
    private $dbname;

    public $connect;

    public  function __construct($dbname='school_management',$username='root',$password='',$host='localhost'){
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->connect();
    }

    private function connect(){
        try{
            $sql = "mysql:host=$this->host;dbname=$this->dbname;";
            $conn = new \PDO($sql,$this->username,$this->password);
            if($conn){
                return $this->connect = $conn;
            }else{
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }
}