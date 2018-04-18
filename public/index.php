<?php

/**
 * LiteFlame - Simple, Liteweight php mvc framework
 */



/**
 * Register the composer autoloader
 */
require __DIR__.'/../vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');
/**
 * Booting the app
 */
$kernel = BootLoader\Kernel::init();

/** initializing the request */
$request = $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

/** process request */
$response = $kernel->process($request);

/** sending response back to client(Browser) */
$response->send();


