<?php 

    namespace App\Http\Controllers;
    use App\Database\Connect;

class Controller{
    protected static $db;
    public static function initialization(){
        $db = new Connect;
        self::$db = $db;
    }

}