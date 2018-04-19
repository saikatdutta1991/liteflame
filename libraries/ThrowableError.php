<?php

namespace Library;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * This class Handles errors and helps to print error in html style clean.
 */
class ThrowableError
{

    protected $error;

    /**
     * initialize with error class instance
     */
    public function __construct(Throwable $error)
    {
        $this->error = $error;
    }


    /**
     * retuns html formated string
     */
    public function formatedErrorString()
    {
        $message = $this->error->getMessage();
        $file = $this->error->getFile();
        $line = $this->error->getLine();
        $fileContent = htmlentities(file_get_contents($file));
    
        return "<h3>Message:</h3> {$message} <br> <h3>File:</h3> : {$file} <br> <h3>Line:</h3> : {$line} <br> <h3>Content:</h3> {$fileContent}";
    }


    /**
     * error response object
     */
    public function getResponse()
    {
         return new \Symfony\Component\HttpFoundation\Response(
            $this->formatedErrorString(), 
            Response::HTTP_INTERNAL_SERVER_ERROR, 
            [
                'content-type' => 'text/html'
            ]);
    }

}