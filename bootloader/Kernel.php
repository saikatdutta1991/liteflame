<?php

namespace BootLoader;

use Symfony\Component\HttpFoundation\Request;
use Error;
use Exception;
use Throwable;

/**
 * This is the heart of this app or call it kernel.
 * Takes request, process and returns response
 */
class kernel
{

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
     *  This is the takes the request object and returns response object
     */
    public function process(Request $request)
    {   
        ini_set('trace_format', 1);
        try {

            $routeFiles = \Library\Config::get('route.routing_files');
            foreach($routeFiles as $route) {
                require $route;
            }

        } catch(Throwable $e) {
            echo self::jTraceEx($e);
            //echo "<pre>";var_dump($e->getTraceAsString());
        }
        


        die;
        return $response = new \Symfony\Component\HttpFoundation\Response(
            'Content',
            \Symfony\Component\HttpFoundation\Response::HTTP_OK,
            array('content-type' => 'text/html')
        );

    }

    public static function jTraceEx($e, $seen=null) {
    $starter = $seen ? 'Caused by: ' : '';
    $result = array();
    if (!$seen) $seen = array();
    $trace  = $e->getTrace();
    $prev   = $e->getPrevious();
    $result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
    $file = $e->getFile();
    $line = $e->getLine();
    while (true) {
        $current = "$file:$line";
        if (is_array($seen) && in_array($current, $seen)) {
            $result[] = sprintf(' ... %d more', count($trace)+1);
            break;
        }
        $result[] = sprintf(' at %s%s%s(%s%s%s)',
                                    count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
                                    count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
                                    count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
                                    $line === null ? $file : basename($file),
                                    $line === null ? '' : ':',
                                    $line === null ? '' : $line);
        if (is_array($seen))
            $seen[] = "$file:$line";
        if (!count($trace))
            break;
        $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
        $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
        array_shift($trace);
    }
    $result = join("\n", $result);
    if ($prev)
        $result  .= "\n" . self::jTraceEx($prev, $seen);

    return $result;
}
    
   

}