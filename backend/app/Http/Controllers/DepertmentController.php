<?php 
    namespace App\Http\Controllers;

use Exception;
use PDO;
use App\Http\Model\Model;



class DepertmentController extends Controller{
    
    private static $table_name = 'depertments';

    protected static function createTable(){
        self::initialization();
        $connection = self::$db->connect;
        $table_name = self::$table_name;
        return Model::createTable($connection,$table_name,"session INT(100) NULL","name VARCHAR(255) NOT NULL");
    }

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
        self::initialization();
        $conn = self::$db->connect;
        if($request->name != '' || $request->session != ''){
            try{
                $conn->beginTransaction();

                $table_name = self::$table_name;

                $countTable = $conn->prepare("SHOW TABLES LIKE :tableName");
                $countTable->bindParam(':tableName',$table_name,PDO::PARAM_STR);
                $countTable->execute();
                if($countTable->rowCount() === 0){
                    DepertmentController::class::createTable();
                }

                
                $name = $request->name;
                $session = $request->session;
                $depertmentInsert = $conn->prepare("INSERT INTO $table_name(name,session) VALUES(:name,:session)");
                $depertmentInsert->bindParam(':name',$name,PDO::PARAM_STR);
                $depertmentInsert->bindParam(':session',$session,PDO::PARAM_INT);
                if($depertmentInsert->execute()){
                    echo json_encode([
                        'status'    => 200,
                        'message'   => 'Depertment added successfully'
                    ]);
                    $conn->commit();
                    exit();
                }else{
                    throw new Exception('Something went wrong. Data not inserted');
                }
            }catch(Exception $e){
                $conn->rollback();
                echo json_encode([
                    'status'    => 401,
                    'message'   => $e->getMessage()
                ]);
                exit();
            }
        }else{
            echo json_encode([
                'status'    => 401,
                'message'   => 'All field is required'
            ]);
            exit();
        }
        
    }


    public static function deleteDepertment($id){
        DepertmentController::class::initialization();
        $conn = self::$db->connect;
        $tableName = self::$table_name;
        try{
            $depertment = $conn->prepare("SELECT * FROM $tableName WHERE id=:id");
            $depertment->bindParam(':id',$id,PDO::PARAM_INT);
            if($depertment->execute()){
                if($depertment->rowCount() !== 0){
                    $delete = $conn->prepare("DELETE FROM $tableName WHERE id=:id");
                    $delete->bindParam(':id',$id,PDO::PARAM_INT);
                    if($delete->execute()){
                        echo json_encode([
                            'status'    => 200,
                            'message'   => 'Delete Successfully'
                        ]);
                        exit();
                    }
                }else{
                    throw new Exception('Depertment not found');
                }
            }
        }catch(Exception $e){
            echo json_encode([
                'status'    => 404,
                'message'   => $e->getMessage()
            ]);
        }
    }

    public static function editDepertment($id){
        DepertmentController::class::initialization();
        $conn = self::$db->connect;
        $tableName = self::$table_name;
        $depertment = $conn->prepare("SELECT * FROM $tableName WHERE id=:id");
        $depertment->bindParam(':id',$id,PDO::PARAM_INT);
        
        if($depertment->execute()){
            echo json_encode([
                'status'        => 200,
                'depertment'   => $depertment->fetch(PDO::FETCH_OBJ)
            ]);
            exit();
        }

    }

    public static function updateDepertment($request){
        DepertmentController::class::initialization();

        $conn = self::$db->connect;
        $tableName = self::$table_name;

        if($request->name == '' || $request->session == ''){
            echo json_encode([
                'status'    => 401,
                'message'   => 'All field is required'
            ]);
        }else{
            try{
                $name = $request->name;
                $session = $request->session;
                $id = $request->id;


                $depertment = $conn->prepare("UPDATE $tableName SET name=:name, session=:session WHERE id=:id");
                $depertment->bindParam(':name',$name,PDO::PARAM_STR);
                $depertment->bindParam(':session',$session,PDO::PARAM_INT);
                $depertment->bindParam(':id',$id,PDO::PARAM_INT);
                if($depertment->execute()){
                    echo json_encode([
                        'status'        => 200,
                        'message'       => 'Depertment Updated Successfully'
                    ]);
                    exit();
                }else{
                    throw new Exception('Something went wrong. Please try again.');
                }
            }catch(Exception $e){
                echo json_encode([
                    'status'    => 401,
                    'message'   => $e->getMessage()
                ]);
            }
        }
    }

}