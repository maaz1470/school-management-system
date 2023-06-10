<?php 
    namespace App\Http\Controllers;

use Exception;
use PDO;

class DepertmentController extends Controller{
    
    private static $table_name = 'depertments';

    public static function getDepertments(){
        self::initialization();
        $conn = self::$db->connect;
        $tableName = self::$table_name;
        try{
            $check_table = $conn->prepare("SHOW TABLES LIKE :tablename");
            $check_table->bindParam(':tablename',$tableName,PDO::PARAM_STR);
            $check_table->execute();
            if($check_table->rowCount() == 0){
                $createTable = $conn->prepare("CREATE TABLE $tableName (
                    id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    session INT(100) NULL,
                    name VARCHAR(255) NOT NULL
                )");

                $createTable->execute();

            } 

            $getDepertments = $conn->prepare("SELECT * FROM $tableName");
            if($getDepertments->execute()){
                echo json_encode([
                    'status'    => 200,
                    'depertments'   => $getDepertments->fetchAll(PDO::FETCH_OBJ)
                ]);
                exit();
            }else{
                echo json_encode([
                    'status'    => 401,
                    'message'   => 'Something went wrong. Please try again.'
                ]);
                exit();
            }
        }catch(Exception $e){
            echo json_encode([
                'status'    => 401,
                'message'   => $e->getMessage()
            ]);
            exit();
        }
    }


    public static function addDepertment($request){
        try{
            if(($request->name == '') || ($request->session == '')){
                throw new Exception("All field is required.");
            }
        }catch(Exception $e){
            echo json_encode([
                'status'    => 401,
                'message'   => $e->getMessage()
            ]);
        }
    }

}