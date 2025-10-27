<?php

use App\Models\ActivityLogs;

if (! function_exists('addToLog')) {
    /* start:Log Activities */
    function addToLog($desc) {
        $log = [];
        $ipaddress = getUserIp();
        if($ipaddress) {
            $log['ip_address'] = getUserIp();
            $log['description'] = $desc;
            $log['fid_user'] = auth()->check() ? auth()->user()->id : NULL;
            $log['url'] = Request::fullUrl();
            $log['method'] = Request::method();
            $log['agent'] = Request::header('user-agent');
            ActivityLogs::create($log);
        }
    }
    /* end:Log Activities */
}
if (! function_exists('getUserIp')) {
    /* start:get User IP Addresss */
    function getUserIp()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = null;
        return $ipaddress;
    }
    /* end:get User IP Addresss */
}
