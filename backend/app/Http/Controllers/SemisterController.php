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


    public static function editSemister($id){
        SemisterController::class::initialization();
        $connection = SemisterController::class::$db->connect;
        $tableName = self::$tableName;
        SemisterController::class::createCurrentTable($connection,$tableName);
        try{
            $findData = $connection->prepare("SELECT * FROM $tableName WHERE id=:id");
            $findData->bindParam(':id',$id,PDO::PARAM_INT);
            if($findData->execute()){
                echo json_encode([
                    'status'    => 200,
                    'semister'  => $findData->fetch(PDO::FETCH_OBJ)
                ]);
                exit();
            }else{
                throw new Exception('Something went wrong. Data not fetch in database');
            }
        }catch(Exception $e){
            echo json_encode([
                'status'    => 500,
                'message'   => $e->getMessage()
            ]);
            exit();
        }
    }

    public static function updateSemister($request){
        SemisterController::class::initialization();
        $connection = SemisterController::class::$db->connect;
        $tableName = self::$tableName;
        SemisterController::class::createCurrentTable($connection,$tableName);

        try{
            if($request->name != '' && $request->id){
                $name = $request->name;
                $id = $request->id;

                $update = $connection->prepare("UPDATE $tableName SET name=:name WHERE id=:id");
                $update->bindParam(':name',$name,PDO::PARAM_STR);
                $update->bindParam(':id',$id,PDO::PARAM_INT);
                if($update->execute()){
                    echo json_encode([
                        'status'    => 200,
                        'message'   => 'Data successfully update.'
                    ]);
                    exit();
                }
            }else{
                throw new Exception('Something went wrong. Please try again.');
            }
        }catch(Exception $e){
            echo json_encode([
                'status'    => 401,
                'message'   => $e->getMessage()
            ]);
            exit();
        }

    }

    public static function deleteSemister($id){
        SemisterController::class::initialization();
        $connection = SemisterController::class::$db->connect;
        $tableName = self::$tableName;
        try{
            $deleteData = $connection->prepare("DELETE FROM $tableName WHERE id=:id");
            $deleteData->bindParam(':id',$id,PDO::PARAM_INT);
            if($deleteData->execute()){
                echo json_encode([
                    'status'    => 200,
                    'message'   => 'Data Delete Successfully'
                ]);
                exit();
            }
        }catch(Exception $e){
            echo json_encode([
                'status'    => 500,
                'message'   => $e->getMessage()
            ]);
            exit();
        }
    }

}