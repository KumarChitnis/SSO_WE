<?php
if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);
        
        if ($value === false) {
            return $default;
        }
        
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        
        return $value;
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config($key, $default = null)
    {
        static $config = null;
        
        if ($config === null) {
            $config = require __DIR__ . '/config/app.php';
        }
        
        return array_get($config, $key, $default);
    }
}

if (!function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        
        if (isset($array[$key])) {
            return $array[$key];
        }
        
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            
            $array = $array[$segment];
        }
        
        return $array;
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to a given URL.
     *
     * @param string $url
     * @param int $statusCode
     * @return void
     */
    function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
}
