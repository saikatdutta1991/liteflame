<?php

namespace BootLoader;

use Symfony\Component\HttpFoundation\Request;

/**
 * This is the heart of this app or call it kernel.
 * Takes request, process and returns response
 */
class kernel
{

    /**
     * make constructor protected
     */
    protected function __construct(){}


    /**
     * initializes the dependencies and returns Kernel instance
     */
    public static function init()
    {
        return new Kernel;
    }


    /**
     *  This is the takes the request object and returns response object
     */
    public function process(Request $request)
    {
        return $response = new \Symfony\Component\HttpFoundation\Response(
            'Content',
            \Symfony\Component\HttpFoundation\Response::HTTP_OK,
            array('content-type' => 'text/html')
        );

    }

}