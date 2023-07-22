<?php 
    
    require_once __DIR__ . '/../vendor/autoload.php';

    use Package\Route\Route;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\CheckController;
    use App\Http\Controllers\DepertmentController;
use App\Http\Controllers\SemisterController;
use App\Http\Controllers\SessionController;


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

    
    // Session Routes

    Route::post('/add-session',function($post){
        SessionController::class::addSession($post);
    });

    Route::get('/get-sessions',function(){
        SessionController::class::getSession();
    });

    Route::get('/edit-session/$id',function($id){
        SessionController::class::editSession($id);
        // print_r($id);
    });

    Route::post('/update-session',function($post){
        SessionController::class::updateSession($post);
    });

    Route::get('/session-delete/$id',function($id){
        SessionController::class::deleteSession($id);
    });



    // Depertment Routes

    Route::get('/get-depertments',function(){
        DepertmentController::class::getDepertments();
    });

    Route::post('/add-depertment',function($post){
        DepertmentController::class::addDepertment($post);
    });

    Route::get('/depertment-delete/$id',function($id){
        DepertmentController::class::deleteDepertment($id);
    });

    Route::get('/depertment/edit/$id',function($id){
        DepertmentController::class::editDepertment($id);
    });

    Route::post('/update-depertment',function($data){
        DepertmentController::class::updateDepertment($data);
    });



    // Semister Route here

    Route::post('/semister/add-semister',function($request){
        SemisterController::class::AddSemister($request);
    });

    Route::get('/semister/get-all',function(){
        SemisterController::class::getSemisters();
    });

    Route::get('/semister/get-data/$id',function($id){
        SemisterController::class::editSemister($id);
    });

    Route::post('/semister/update-semister',function($data){
        SemisterController::class::updateSemister($data);
    });

    Route::get('/semister/delete/$id',function($id){
        SemisterController::class::deleteSemister($id);
    });



    


    Route::get('/something/$id',function($id){
        print_r($id);
    });

    Route::any('/404',function(){
        http_response_code(404);
    });