<?php

if (!function_exists('get_real_ip')) {
    /**
     * 取得真實IP (避免取到CDN機器IP)
     *
     * @param  string  $ip
     * @return string
     */
    function get_real_ip($ip)
    {
        try {
            if (in_array(config('common.APP_ENV'), ['experience', 'production'])) {
                if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
                    $tmp = $_SERVER["HTTP_CLIENT_IP"];
                } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                    $tmp = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else {
                    $tmp = $_SERVER["REMOTE_ADDR"];
                }
                $tmp = explode(',', $tmp);
                $ip = trim($tmp[0]);
            }
            return $ip;
        } catch (\Exception $e) {
            return $ip;
        }
    }
}
