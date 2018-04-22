<?php

namespace Library;

/**
 * Resolves controller instance
 * Call controller method
 * return reponse to router
 */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use ReflectionClass;

class Controller
{

    /**
     * store global request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }



    /**
     * resolve class and return instance
     */
    protected function make($classname)
    {
        $rClass = new ReflectionClass($classname);
        
        if(!$rClass->hasMethod('__construct')) {
            return $rClass->newInstance();
        }

        $rClassConstructMethod = $rClass->getMethod('__construct');
        $constructParams = $rClassConstructMethod->getParameters();
        
        if(!count($constructParams)) {
            return $rClass->newInstance();
        }

        $args = [];
        foreach($constructParams as $param) {
            $args[] = $this->make($param->getClass()->name);
        }
        
        return $rClass->newInstanceArgs($args);
      
    }



    /**
     * takes route handler and resolve and call return response
     */
    public function resolve($handler)
    {
        $response = null;
        $callable = null;

        /** if handler is closure */
        if($handler instanceof Closure) {
            $callable = $handler;
        } 
        // if string and pattern must be Controller@method
        elseif(is_string($handler)) {

            list($classname, $methodName) = explode('@', $handler);
            $cInstance = $this->make($classname); // controller instance
            $callable = [$cInstance, $methodName];
        }

        $response = $this->makeResponse($this->invoke($callable));
       
        return $response;
    }


    /**
     * make response
     * takes controller return
     */
    protected function makeResponse($cReturn)
    {
        /** string controller return */
        if(is_string($cReturn)) {
            return new Response($cReturn, Response::HTTP_OK, ['content-type' => 'text/html']);
        }
        /** template controller return */
    }




    /**
     * invoke controller with request parameter and return response
     */
    protected function invoke($callable)
    {
        return call_user_func_array($callable, [$this->request]);
    }


}