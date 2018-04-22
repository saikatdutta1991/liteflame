<?php

/**
 * This file contains helpers functions
 */

if(!function_exists('dump')) {

    function dump($var)
    {
        echo "<pre>";var_dump($var);exit;
    }

}