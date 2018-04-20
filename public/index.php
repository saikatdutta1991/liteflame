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

/** initializing the request */
$request = $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

/**
 * Booting the app
 */
$kernel = new BootLoader\Kernel($request);

/** process request */
$response = $kernel->process();

/** sending response back to client(Browser) */
$response->send();


