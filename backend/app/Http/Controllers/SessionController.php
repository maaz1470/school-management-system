<?php 
    namespace App\Http\Controllers;

use App\Database\Connect;
use Exception;
use PDO;

class SessionController{

    private static $db;
    private static $table_name;
    public static function initialization(){
        $connect = new Connect;
        self::$db = $connect;
        self::$table_name = 'sessions';
    }
    public static function addSession($request){
        SessionController::class::initialization();
        try{
            if($request->name == ''){
                throw new Exception("Session name field is required.");
            }else{
                $name = $request->name;
                $conn = self::$db->connect;
                $tablename = self::$table_name;
                $table = $conn->prepare("SHOW TABLES LIKE :tablename");
                $table->bindParam(':tablename',$tablename,PDO::PARAM_STR);
                $table->execute();
                if($table->rowCount() == 0){
                    $createTable = $conn->prepare("CREATE TABLE $tablename (
                        id int(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(255) NOT NULL
                    )");
                    $createTable->execute();
                }
                
                $session = $conn->prepare("INSERT INTO $tablename(name) VALUES(:name)");
                $session->bindParam(':name',$name,PDO::PARAM_STR);
                if($session->execute()){
                    echo json_encode([
                        'status'    => 200,
                        'message'=>'Session Added successfully'
                    ]);
                    exit();
                }else{
                    throw new Exception('Something went wrong. Session is not saved in Database.');
                }
            }
        }catch(Exception $e){
            echo json_encode(['status'=>401,'message'=>$e->getMessage()]);
            exit();
        }
    }

    public static function getSession(){
        SessionController::class::initialization();
        $conn = self::$db->connect;
        $table_name = self::$table_name;
        $session = $conn->prepare("SELECT * FROM $table_name");
        $session->execute();
        if($sessions = $session->fetchAll(PDO::FETCH_OBJ)){
            echo json_encode(['status'  => 200,'sessions'   => $sessions]);
        }else{
            echo json_encode(['status'  => 401,'message'    => `We cannot fetch data from {$table_name} table`]);
        }
    }

    public static function editSession($id){
        SessionController::class::initialization();
        try{
            $conn = self::$db->connect;
            $tableName = self::$table_name;
            $table_id = $id;
            $results = $conn->prepare("SELECT * FROM $tableName WHERE id=:id");
            $results->bindParam(':id',$id,PDO::PARAM_INT);
            $results->execute();
            $session = $results->fetch();
            
            if($session){
                echo json_encode([
                    'status'  => 200,
                    'session'   => $session
                ]);
                exit();
            }else{
                echo json_encode([
                    'status'    => 404,
                    'message'   => 'Data not found'
                ]);
                exit();
            }
        }catch(Exception $e){
            echo json_encode(['status'=>401,'message'=>$e->getMessage()]);
        }
    }

    public static function updateSession($request){
        SessionController::class::initialization();

        try{
            $name = $request->name;
            $id = $request->id;
            if($name != '' && $id != ''){
                $tableName = self::$table_name;

                $conn = self::$db->connect;

                $check_session = $conn->prepare("SELECT * FROM sessions WHERE id=:id");

                $check_session->bindParam(':id',$id,PDO::PARAM_INT);
                $check_session->execute();
                if(count($check_session->fetchAll()) == 1){
                    $session = $conn->prepare("UPDATE $tableName SET name=:name WHERE id=:id");
                    $session->bindParam(':name',$name,PDO::PARAM_STR);
                    $session->bindParam(':id',$id,PDO::PARAM_INT);
                    if($session->execute()){
                        echo json_encode([
                            'status'    => 200,
                            'message'   => 'Session update successfully.'
                        ]); 
                        exit();
                    }else{
                        echo json_encode([
                            'status'    => 401,
                            'message'   => 'Something went wrong. Session update failed.'
                        ]);
                        exit();
                    }
                }else{
                    echo json_encode([
                        'status'    => 404,
                        'message'   => 'Session not found.'
                    ]);
                    exit();
                }
            }else{
                throw new Exception("All fieled is required.");
            }

        }catch(Exception $e){
            echo json_encode([
                'status'    => 401,
                'message'   => $e->getMessage()
            ]);
        }
    }


    public static function deleteSession($id){
        SessionController::class::initialization();
        try{
            $conn = self::$db->connect;
            $tableName = self::$table_name;
            $check_session = $conn->prepare("SELECT id FROM $tableName WHERE id=:id");
            $check_session->bindParam(':id',$id,PDO::PARAM_INT);
            $check_session->execute();
            if($check_session->rowCount() === 1){
                $session = $conn->prepare("DELETE FROM $tableName WHERE id=:session_id");
                $session->bindParam(':session_id',$id,PDO::PARAM_INT);
                if($session->execute()){
                    echo json_encode([
                        'status'    => 200,
                        'message'   => 'Session Delete successfully'
                    ]);
                    exit();
                }else{
                    echo json_encode([
                        'status'    => 401,
                        'message'   => 'Something went wrong. Session is not deleted'
                    ]);
                    exit();
                }
            }else{
                echo json_encode([
                    'status'    => 404,
                    'message'   => 'Session not found'
                ]);
            }
        }catch(Exception $e){
            echo json_encode([
                'status'    => 401,
                'message'   => $e->getMessage()
            ]);
            exit();
        }
    }
}