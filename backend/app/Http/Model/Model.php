<?php 

    namespace App\Http\Model;
    use PDO;

class Model{
    public static function createTable($connection,$tableName, ...$arguments){
        $check_table = $connection->prepare("SHOW TABLES LIKE :tableName");
        $check_table->bindParam(':tableName',$tableName,PDO::PARAM_STR);
        $check_table->execute();

        if($check_table->rowCount() === 0){
            $columnDefs = "";
            foreach($arguments as $someTable){
                $columnDefs .= $someTable.', ';
            }
            $columnDefs .= "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
            $createTable = $connection->prepare("CREATE TABLE $tableName(
                id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                $columnDefs
            )");
            $createTable->execute();
            return true;
        }
        return false;
    }
}