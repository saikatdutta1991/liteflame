<?php

namespace Library;

use Closure;
use Library\Config;
use Library\Controller;
use Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * this class used to load routes
 */
class Route
{

    /**
     * holds current request route
     */
    protected $currentRequestRouteFromUrl;

    /**
     * holds request
     */
    protected $request;


    /**
     * holds get routes
     */
    protected static $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * store route files
     */
    protected $routeFiles;


    /**
     * consturctor
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->routeFiles = Config::get('route.routing_files');
    }



    /**
     * get all routes
     */
    public function getAll()
    {
        return self::$routes;
    }


    /**
     * load all route files
     */
    public function loadRoutes()
    {
        foreach($this->routeFiles as $route) {
            require $route;
        }
        
        return $this;
    }


    /**
     * register get routes
     */
    public static function get(string $routename, $handler)
    {
        $routename = '/'.trim($routename, '/');
        self::$routes['GET'][$routename] = $handler;
    }



    /**
     * register post routes
     */
    public static function post(string $routename, $handler)
    {
        $routename = '/'.trim($routename, '/');
        self::$routes['POST'][$routename] = $handler;
    }



    /**
     * find matched route from request and pass control to controller
     */
    public function process()
    {
        /** find route handler */
    
        $method = $this->request->getMethod();
        $currRoute = $this->getCurrentRouteFromUrl();

        if(!isset(self::$routes[$method][$currRoute])) {
            throw new \Library\Exceptions\RouteNotFound("Route not found");
        }

        /** search and route match get handler */
        $handler = self::$routes[$method][$currRoute];
        
        /** controller instance for resolving and invoking controller */
        $controller = new Controller($this->request);
        $response = $controller->resolve($handler);

        return $response;
        
    }



    /**
     * returns current request route string in request url
     */
    public function getCurrentRouteFromUrl()
    {        
        if(is_string($this->currentRequestRouteFromUrl)) {
            return $this->currentRequestRouteFromUrl;
        }

        //remove index.php from request server bag script name
        $scriptname = str_replace('index.php', '', $this->request->server->get('SCRIPT_NAME'));
        $requestRoute = str_replace($scriptname, '/', $this->request->server->get('REQUEST_URI'));
        return $this->currentRequestRouteFromUrl = $requestRoute;
    }

}