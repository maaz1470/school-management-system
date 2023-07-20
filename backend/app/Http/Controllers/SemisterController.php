<?php 

    namespace App\Http\Controllers;
    use App\Http\Controllers\Controller;
    use App\Http\Model\Model;
    use PDO;
    use Exception;
class SemisterController extends Controller{
    
    protected static $tableName = 'semisters';

    protected static function createCurrentTable($connection,$tableName){
        return Model::createTable($connection,$tableName,"name VARCHAR(255) NOT NULL");
    }

    public static function AddSemister($request){
        SemisterController::class::initialization();
        $connection = SemisterController::$db->connect;
        $tableName = SemisterController::$tableName;

        SemisterController::class::createCurrentTable($connection,$tableName);

        if($request->name != ''){
            try{
                $connection->beginTransaction();

                $name = $request->name;

                $SemisterSave = $connection->prepare("INSERT INTO $tableName(name) VALUES(:name)");
                $SemisterSave->bindParam(':name',$name,PDO::PARAM_STR);
                if($SemisterSave->execute()){
                    echo json_encode([
                        'status'    => 200,
                        'message'   => 'Data Successfully Insert'
                    ]);
                    $connection->commit();
                    exit();
                }else{
                    throw new Exception('Data not Saved.');
                }
            }catch(Exception $e){
                $connection->rollback();
                echo json_encode([
                    'status'    => 500,
                    'message'   => $e->getMessage()
                ]);
                exit();
            }
        }else{
            echo json_encode([
                'status'    => 401,
                'message'   => 'All Field is required'
            ]);
            exit();
        }


    }

    public static function getSemisters(){
        SemisterController::class::initialization();
        try{

            $conection = SemisterController::class::$db->connect;
            $tableName = self::$tableName;


            SemisterController::class::createCurrentTable($conection,$tableName);


            $semisters = $conection->prepare("SELECT * FROM $tableName");

            if($semisters->execute()){
                echo json_encode([
                    'status'    => 200,
                    'semisters' => $semisters->fetchAll(PDO::FETCH_OBJ)
                ]);
                exit();
            }else{
                throw new Exception('Something went wrong. Data not fetched to database.');
            }

            


        }catch(Exception $e){
            echo json_encode([
                'status'    => 500,
                'message'   => 'Server Problem'
            ]);
        }
    }

}