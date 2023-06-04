<?php 
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With, Authorization');

    require_once __DIR__ . '/../vendor/autoload.php';

    use Package\Route\Route;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\CheckController;
    Route::initialize();



    Route::post('/user-login',function($post){
        HomeController::userLogin($post);
    });

    Route::post('/user-registration',function($request){
        HomeController::userRegistration($request);
        // print_r(json_encode($post));
    });


    Route::get('/check-auth',function(){
        CheckController::AuthCheck();
    });

    
    // CheckController::AuthCheck(function($data){
    //     if($data['status'] == 200){
    //         Route::get('/private',function(){
    //             echo "This is sabbir";
    //         });
    //     }else{
    //         print_r("Your are not allowed user");
    //     }
    // });

    Route::get('/h',function(){
        print("Hello World");
    });


    


    Route::get('/something/$id',function($id){
        http_response_code(404);
    });

    Route::any('/404',function(){
        http_response_code(404);
    });