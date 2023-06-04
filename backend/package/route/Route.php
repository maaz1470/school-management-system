<?php

    namespace Package\Route;

class Route{
    public static function initialize(){
        

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("Access-Control-Allow-Origin: http://localhost:3000");
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
            header("Access-Control-Allow-Headers: Authorization, Content-Type");
            header("Access-Control-Allow-Credentials: true");
            exit;
        }else{
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json');
            header('Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With, Authorization');
        }
    }
    public static function get($route, $path_to_include)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            self::route($route, $path_to_include);
        }
    }
    public static function post($route, $path_to_include)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            self::route($route, $path_to_include);
        }
    }
    public static function put($route, $path_to_include)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            self::route($route, $path_to_include);
        }
    }
    public static function patch($route, $path_to_include)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
            self::route($route, $path_to_include);
        }
    }
    public static function delete($route, $path_to_include)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            self::route($route, $path_to_include);
        }
    }
    public static function any($route, $path_to_include)
    {
        self::route($route, $path_to_include);
    }
    private static function route($route, $path_to_include)
    {
        $callback = $path_to_include;
        if (!is_callable($callback)) {
            if (!strpos($path_to_include, '.php')) {
                $path_to_include .= '.php';
            }
        }
        if ($route == "/404") {
            if(is_callable($path_to_include)){
                return $path_to_include();
            }else{
                
                include_once __DIR__ . "/$path_to_include";
                exit();
            }
        }
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/');
        $request_url = strtok($request_url, '?');
        $route_parts = explode('/', $route);
        $request_url_parts = explode('/', $request_url);
        array_shift($route_parts);
        array_shift($request_url_parts);
        if ($route_parts[0] == '' && count($request_url_parts) == 0) {
            // Callback function
            if (is_callable($callback)) {
                call_user_func_array($callback, []);
                exit();
            }
            include_once __DIR__ . "/$path_to_include";
            exit();
        }
        if (count($route_parts) != count($request_url_parts)) {
            return;
        }
        $parameters = [];
        for ($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
            $route_part = $route_parts[$__i__];
            if (preg_match("/^[$]/", $route_part)) {
                $route_part = ltrim($route_part, '$');
                array_push($parameters, $request_url_parts[$__i__]);
                $$route_part = $request_url_parts[$__i__];
            } else if ($route_parts[$__i__] != $request_url_parts[$__i__]) {
                return;
            }
        }
        // Callback function
        if (is_callable($callback)) {
            if(isset($_POST) && is_callable($callback)){
                $callback((object)$_POST);
                exit();
            }
            call_user_func_array($callback, $parameters);
            exit();
        }
        include_once __DIR__ . "/$path_to_include";
        exit();
    }
    public static function out($text)
    {
        echo htmlspecialchars($text);
    }

    public static function set_csrf()
    {
        session_start();
        if (!isset($_SESSION["csrf"])) {
            $_SESSION["csrf"] = bin2hex(random_bytes(50));
        }
        echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
    }

    public static function is_csrf_valid()
    {
        session_start();
        if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
            return false;
        }
        if ($_SESSION['csrf'] != $_POST['csrf']) {
            return false;
        }
        return true;
    }
}