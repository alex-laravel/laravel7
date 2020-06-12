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
