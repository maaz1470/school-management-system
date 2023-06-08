<?php 
    namespace App\Http\Controllers;

class DepertmentController extends Controller{
    
    public static function getDepertments(){
        self::class::initialization();
        $conn = self::$db->connect;

        print_r($conn);
    }
}