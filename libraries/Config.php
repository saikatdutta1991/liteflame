<?php

namespace Library;

/**
 * This class helps to get configs from config folder
 */
class config
{

    protected static $configBasePath;

    /**
     * used to get a specific config
     */
    public static function get(string $configFileAndKey = '')
    {
        list($configFile, $key) = explode(".", $configFileAndKey);
        $config = self::getConfigFileContent($configFile);

        return isset($config[$key]) ? $config[$key] : null;
    }



    /**
     * returns the config file content must return array
     */
    protected static function getConfigFileContent($filename)
    {
        $file = self::$configBasePath.'/'.$filename.'.php';
        return (file_exists($file)) ? include $file : [];
    }


    /**
     * static variable initialization
     */
    public static function initStatic()
    {
        self::$configBasePath = dirname(dirname(__FILE__)).'/config/';
    }

}

//initializes static variables
Config::initStatic();