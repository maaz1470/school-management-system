<?php 

    namespace App\Http\Controllers;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    use App\Database\Connect;
    use PDO;
    use Exception;
    use UnexpectedValueException;
    use Package\Route\Route;
class CheckController{
    private static $db;
    public static function initialization(){
        $connect = new Connect;
        self::$db = $connect;
    }
    public static function AuthCheck(){
        CheckController::class::initialization();
        $pass_user = [
            'status'    => 403,
            'message'   => 'Unathenticated!'
        ];
        if($_SERVER['REQUEST_METHOD'] == ('GET' || 'POST')){
            
            $conn = self::$db->connect;
            
            if(isset($_SERVER['HTTP_ORIGIN']) || $_SERVER){
                $secret_key = 'Rahat Hossain';
            
                $allheaders = getallheaders();
                if(isset($allheaders['Authorization'])){
                    try{
                        $token = $allheaders['Authorization'];
                        $main_token = explode(' ',$token);
                        $secret_token = JWT::decode($main_token[1],new Key($secret_key,'HS256'));
                        if(isset($secret_token->data->id) && $secret_token->data->id){


                            $id = $secret_token->data->id;

                            $check_user = $conn->prepare("SELECT id FROM admins WHERE id=:id");
                            $check_user->bindParam(':id',$id,PDO::PARAM_INT);
                            $check_user->execute();
                            $user = $check_user->fetch(PDO::FETCH_OBJ);
                            if($user){
                                echo json_encode([
                                    'status'    => 200,
                                    'message'   => 'Authenticated'
                                ]);
                                exit();
                            }else{
                                echo json_encode([
                                    'status'    => 403,
                                    'message'   => 'Unathenticated!'
                                ]);
                                exit();
                            }
                        }
                    }catch(UnexpectedValueException $e){
                        echo json_encode([
                            'status'    => 403,
                            'message'   => 'Unexpected Value!'
                        ]);
                        exit();
                    }catch(\DomainException $e){
                        echo json_encode([
                            'status'    => 403,
                            'message'   => 'Unauthenticated'
                        ]);
                        exit();
                    }catch(Exception $e){
                        echo json_encode([
                            'status'    => 500,
                            'message'   => 'Server Error'
                        ]);
                        exit();
                    }
                }
            }

        }
        
        echo json_encode($pass_user);
        // call_user_func($callback, $pass_user);
    }
}