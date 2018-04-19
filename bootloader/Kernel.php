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
     * store alias classes
     */
    protected $aliases;

    /**
     * holds kernel singleton instance
     */
    protected static $kernel;

    /**
     * make constructor protected
     */
    protected function __construct(){}


    /**
     * initializes the dependencies and returns Kernel instance
     */
    public static function init()
    {
        return self::$kernel ?: (self::$kernel = new Kernel);
    }



    /**
     * load class aliases
     */
    protected function loadAliases()
    {
        $this->aliases = Config::get('app.aliases');
        foreach($this->aliases as $alias => $class) {
            class_alias($class, $alias, true);
        }
    }




    /**
     *  This is the takes the request object and returns response object
     */
    public function process(Request $request)
    {   
        
        try {

            /** load app aliases classes */
            $this->loadAliases();

            /** load all route files */
            Route::loadRoutes();

            /** process request */
            Route::process();

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