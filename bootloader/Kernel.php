<?php

namespace BootLoader;

use Library\Config;
use Error;
use Symfony\Component\HttpFoundation\Request;
use Library\Route;
use Library\ThrowableError;
use Throwable;


/**
 * This is the heart of this app or call it kernel.
 * Takes request, process and returns response
 */
class kernel
{

    /**
     * store request object
     */
    protected $request;


    /**
     * store alias classes
     */
    protected $aliases;


    /**
     * store route object
     */
    protected $route;



    /**
     * make constructor protected
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->route = new Route($this->request);
        $this->aliases = Config::get('app.aliases');
    }


    /**
     * load class aliases
     */
    protected function loadAliases()
    {
        foreach($this->aliases as $alias => $class) {
            class_alias($class, $alias, true);
        }
        
        return $this;
    }




    /**
     *  This is the beating of heart
     *  Process request and returns response object
     */
    public function process()
    {   
        
        try {

            /** load app aliases classes */
            $this->loadAliases();

            /** load all route files and start process */
            $this->route->loadRoutes()->process();

        } catch(Throwable $e) {
            return (new ThrowableError($e))->getResponse();
        }
        

        die;
        return $response = new \Symfony\Component\HttpFoundation\Response(
            '<h1>success</h1>',
            \Symfony\Component\HttpFoundation\Response::HTTP_OK,
            array('content-type' => 'text/html')
        );

    }


}