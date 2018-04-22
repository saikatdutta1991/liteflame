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
     * load core helper functions
     */
    protected function loadHelpers()
    {
        require dirname(dirname(__FILE__)).'/libraries/helpers.php';
    }



    /**
     *  This is the beating of heart
     *  Process request and returns response object
     */
    public function process()
    {   
        
        $reponse = null;
        
        try {
            
            /** load helper functions */
            $this->loadHelpers();

            /** load app aliases classes */
            $this->loadAliases();

            /** load all route files and start process */
            $response = $this->route->loadRoutes()->process();

        } catch(Throwable $e) {
            $response = (new ThrowableError($e))->getResponse();
        }

        return $response;
    
    }


}