<?php

if (!function_exists('routeHome')) {

    /**
     * @return string
     */
    function routeHome()
    {
        return '/';
    }
}

if (!function_exists('generateRememberToken')) {

    /**
     * @return string
     * @throws Exception
     */
    function generateRememberToken()
    {
        return bin2hex(random_bytes(32));
    }
}

if (!function_exists('generateVerifyEmailHash')) {

    /**
     * @param string $hashKey1
     * @param string $hashKey2
     * @return string
     */
    function generateVerifyEmailHash($hashKey1, $hashKey2)
    {
        return sha1(implode('|', [$hashKey1, $hashKey2]));
    }
}

if (!function_exists('includeRouteFiles')) {

    /**
     * @param string $folder
     */
    function includeRouteFiles($folder)
    {
        try {
            $recursiveDirectoryIterator = new RecursiveDirectoryIterator($folder);
            $recursiveIteratorIterator = new RecursiveIteratorIterator($recursiveDirectoryIterator);

            while ($recursiveIteratorIterator->valid()) {
                if (!$recursiveIteratorIterator->isDot()
                    && $recursiveIteratorIterator->isFile()
                    && $recursiveIteratorIterator->isReadable()
                ) {
                    require $recursiveIteratorIterator->key();
                }

                $recursiveIteratorIterator->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
