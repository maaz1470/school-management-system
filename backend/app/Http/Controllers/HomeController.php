<?php 
    namespace App\Http\Controllers;
    use App\Database\Connect;
    use Exception;
    use PDO;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception as MailException;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
class HomeController{
    public static $db;
    public static function initialization(){
        $db = new Connect();
        self::$db = $db;
    }


    public static function home(){
        echo "Hello World";
    }

    public static function userRegistration($request){
        HomeController::class::initialization();
        
        $error = '';
        if($request->name != ''){
            if($request->email != ''){
                if(filter_var($request->email,FILTER_VALIDATE_EMAIL)){
                    if($request->password != ''){
                        $name = $request->name;
                        $email = $request->email;
                        $password = password_hash($request->password,PASSWORD_BCRYPT);
                        $role = 1;
                        $verify = false;
                        $table_name = 'admins';
                        $exist_table = self::$db->connect->prepare("SHOW TABLES LIKE :tableName");
                        $exist_table->bindParam(':tableName',$table_name);
                        $exist_table->execute();
                        $conn = self::$db->connect;
                        if($exist_table->rowCount() == 0){
                            
                            try{
                                $query = "CREATE TABLE $table_name (
                                    id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                    name VARCHAR(255) NOT NULL,
                                    email VARCHAR(255) NOT NULL UNIQUE,
                                    password VARCHAR(255) NOT NULL,
                                    verify TINYINT(1) DEFAULT 0 NULL,
                                    role TINYINT(1) DEFAULT 0
                                )";
                                $create_table = self::$db->connect->prepare($query);
                                if(!$create_table->execute()){
                                    throw new Exception("Table is not created.");
                                }
                            }catch(Exception $e){
                                echo json_encode(['status'=>401,'message' => $e->getMessage()]);
                                exit();
                            }
                            
    
                        }

                        try{
                            $check_email = $conn->prepare("SELECT id,email FROM admins WHERE email=:email");
                            $check_email->bindParam(':email',$email,PDO::PARAM_STR);
                            $check_email->execute();
                            $all_emails = $check_email->fetchAll();

                            if(count($all_emails) >= 1){
                                throw new Exception("Email Allready exists.");
                            }else{
                                try{
                                    $conn->beginTransaction();
                                    
                                    $admin = $conn->prepare("INSERT INTO admins(name,email,password,verify,role) VALUES(:name,:email,:password,:verify,:role)");
                                    $admin->bindParam(':name',$name,PDO::PARAM_STR);
                                    $admin->bindParam(':email',$email,PDO::PARAM_STR);
                                    $admin->bindParam(':password',$password,PDO::PARAM_STR);
                                    $admin->bindParam(':role',$role,PDO::PARAM_INT);
                                    $admin->bindParam(':verify',$verify,PDO::PARAM_BOOL);
                                    if(!$admin->execute()){
                                        throw new Exception("Something went wrong. Admin information not saved in database.");
                                    }else{
                                        // $id = $conn->lastInsertId();
                                        // $users = $conn->prepare("SELECT id,name,email FROM admins WHERE id=:id");
                                        // $users->bindParam(':id',$id,PDO::PARAM_INT);
                                        // $users->execute();
                                        // $user = $users->fetch(PDO::FETCH_OBJ);
                                        
                                        // $mail = new PHPMailer(true);
                                        // try{
                                            
                                        //     $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                                        //     $mail->isSMTP();                                            //Send using SMTP
                                        //     $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                                        //     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                                        //     $mail->Username   = 'rahat1470.com@gmail.com';                     //SMTP username
                                        //     $mail->Password   = 'pnzlzmhgglhxbsao';                               //SMTP password
                                        //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                                        //     $mail->Port       = 587;

                                        //     $mail->setFrom('rahat1470.com@gmail.com','Rahat Hossain');
                                        //     $mail->addAddress($user->email, $user->name);

                                        //     $mail->isHTML(true);
                                        //     $mail->Subject = 'Email Verification';
                                        //     $mail->Body = "This is mail <b>Body</b>";
                                        //     $mail->AltBody = "This is alt body";

                                        //     if(!$mail->send()){
                                        //         throw new Exception("Mail sending problem.");
                                        //     }else{
                                        //         echo json_encode(['success' => 'Admin Registration Successfully']);
                                        //         $conn->commit();
                                        //         exit();
                                        //     }


                                        // }catch(Exception $e){
                                        //     echo json_encode(['error' => $e->getMessage()]);
                                        //     exit();
                                        // }

                                        echo json_encode(['message' => 'Admin saved successfully','status'=>200]);
                                        $conn->commit();
                                        exit();


                                    }
                                }catch(Exception $e){
                                    echo json_encode(['status'=>401,'message' => $e->getMessage()]);
                                    exit();
                                }
                            }
                        }catch(Exception $e){
                            echo json_encode(['status'=>401,'message' => $e->getMessage()]);
                        }
                        
                        
                        
    
                    }else{
                        $error = 'Password field is required';
                    }
                }else{
                    $error = "Email address not valid";
                }
            }else{
                $error = 'Email field is required';
            }
        }else{
            $error = 'Name field is required';
        }

        if($error != ''){
            echo json_encode(['status'=>401,'message' => $error]);
        }
    }

    public static function userLogin($request){
        HomeController::class::initialization();
        $error = '';
        if($request->email != ''){
            if(filter_var($request->email,FILTER_VALIDATE_EMAIL)){
                if($request->password != ''){
                

                    $email = $request->email;
                    $password = $request->password;

                    $conn = self::$db->connect;
                    
                    try{
                        $check_email = $conn->prepare("SELECT id,email,password FROM admins WHERE email=:email");
                        $check_email->bindParam(':email',$email,PDO::PARAM_STR);
                        $check_email->execute();
                        $findEmail = $check_email->fetch();
                        if($findEmail){
                            if(count($findEmail) == 0){
                                throw new Exception('Email not match.');
                            }else{
                                if(password_verify($password,$findEmail['password'])){
                                    
                                    $key = 'Rahat Hossain';
                                    if($request->rememberMe){
                                        $payload = [
                                            'iss' => 'localhost',
                                            'aud' => 'localhost',
                                            'iat' => time(),
                                            'data'=> [
                                                'id'    => $findEmail['id']
                                            ]
                                        ];
                                    }else{
                                        $payload = [
                                            'iss' => 'localhost',
                                            'aud' => 'localhost',
                                            'iat' => time(),
                                            'exp' => time() + 86400,
                                            'data'=> [
                                                'id'    => $findEmail['id']
                                            ]
                                        ];
                                    }
    
                                    $token = JWT::encode($payload,$key,'HS256');
    
                                    
                                    echo json_encode([
                                        'status'    => 200,
                                        'id'        => $findEmail['id'],
                                        'token'     => $token,
                                        'message'   => 'Successfully Login'
                                    ]);
    
                                    exit();
    
                                }else{
                                    throw new Exception('Email or Password not matched');
                                    
                                }
                            }
                        }else{
                            throw new Exception("Email or Password not matched");
                        }
                    }catch(Exception $e){
                        echo json_encode(['status' => 401,'message' => $e->getMessage()]);
                        exit();
                    }

                }else{
                    $error = 'Password field is required';
                }
            }else{
                $error = 'Email is not valid.';
            }
        }else{
            $error = 'Email field is required';
        }
        if($error != ''){
            echo json_encode(['status' => 401,'message' => $error]);
        }
    }
}