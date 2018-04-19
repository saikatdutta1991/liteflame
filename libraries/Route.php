<?php

namespace Library;

use Library\Config;

/**
 * this class used to load routes
 */
class Route
{

    /**
     * holds get routes
     */
    protected static $routes = [
        'get' => [],
        'posts' => []
    ];


    /**
     * load all route files
     */
    public static function loadRoutes()
    {
        $routeFiles = Config::get('route.routing_files');
        foreach($routeFiles as $route) {
            require $route;
        }
    }


    /**
     * register get routes
     */
    public static function get(string $routename, $handler)
    {
        self::$routes['get'][$routename] = $handler;
    }



    /**
     * find matched route from request and pass controll to controller
     */
    public static function process()
    {
        global $request;
        //remove index.php from request script name
        $scriptname = str_replace('index.php', '', $request->server->get('SCRIPT_NAME'));
        $requestRoute = str_replace($scriptname, '', $request->server->get('REQUEST_URI'));

        
        


        call_user_func_array(self::$routes['get'][$requestRoute], [])
        echo "<pre>";var_dump();die;
    }


}