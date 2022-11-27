<?php

use PackageVersions\Versions;

if (!function_exists('build_external_url')) {
    /**
     * Build url from host , path , query,....
     *
     * @param string $host
     * @param string|null $path
     * @param array $query
     * @param string|null $schema
     * @param int|null $port
     * @return string
     */
    function build_external_url(string $host, string $path = null, array $query = [], string $schema = null, int $port = null): string
    {
        $url = $host;
        if (null !== $port) {
            $url .= ':' . $port;
        }
        if (null !== $path) {
            $url .= '/' . ltrim($path, '/');
        }
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }
        return (null === $schema ? $url : ($schema . '://' . $url));
    }
}

if (!function_exists('array_multiple_keys_exist')) {
    /**
     * Check exist multiple key in array
     * @param array $needles
     * @param array $haystack
     * @return bool
     */
    function array_multiple_keys_exist(array $needles, array $haystack): bool
    {
        foreach ($needles as $needle) {
            if (!array_key_exists($needle, $haystack)) return false;
        }

        return true;
    }
}

function backpack_pro(): string
{
    return Versions::getVersion('backpack/crud');
}
