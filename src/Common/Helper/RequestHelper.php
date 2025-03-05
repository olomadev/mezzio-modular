<?php

declare(strict_types=1);

namespace Common\Helper;

class RequestHelper
{
    /**
     * Get origin
     *
     * https://stackoverflow.com/questions/276516/parsing-domain-from-a-url
     * 
     * @param  string $host $server['SERVER_NAME']
     * @return string|null
     */
    public static function getOrigin($host) {
        if (! $host) {
            return $host;
        }
        if (filter_var($host, FILTER_VALIDATE_IP)) { // IP address returned as domain
            return $host; //* or replace with null if you don't want an IP back
        }
        $domainArray = explode(".", str_replace('www.', '', $host));
        $count = count($domainArray);
        if( $count >= 3 && strlen($domainArray[$count-2])==2 ) {
            // SLD (example.co.uk)
            return implode('.', array_splice($domainArray, $count-3,3));
        } else if ($count >= 2 ) {
            // TLD (example.com)
            return implode('.', array_splice($domainArray, $count-2,2));
        }
    }
    /**
     * Get user real ip if proxy used
     * 
     * @param  string|null  $default default value
     * @param  integer $options filter_var options
     * @return string|null
     */
    public static function getRealUserIp($default = null, $options = 12582912) 
    {
        // 
        // cloudflare support
        // 
        $HTTP_CF_CONNECTING_IP = isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : getenv('HTTP_CF_CONNECTING_IP');
        $HTTP_X_FORWARDED_FOR = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : getenv('HTTP_X_FORWARDED_FOR');
        $HTTP_CLIENT_IP = isset($_SERVER["HTTP_CLIENT_IP"]) ? $_SERVER["HTTP_CLIENT_IP"] : getenv('HTTP_CLIENT_IP');
        $REMOTE_ADDR = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : getenv('REMOTE_ADDR');
        $allIps = explode(",", "$HTTP_X_FORWARDED_FOR,$HTTP_CLIENT_IP,$HTTP_CF_CONNECTING_IP,$REMOTE_ADDR");
        foreach ($allIps as $ip) {
            if ($ip = filter_var($ip, FILTER_VALIDATE_IP, $options)) {
                break;
            }
        }
        if ($ip == null) {
            $default = $REMOTE_ADDR;
        }
        return $ip ? $ip : $default;
    }
}