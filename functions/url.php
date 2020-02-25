<?php

/**
 * 判断是否https
 * @return bool
 */
function is_ssl()
{
    if (isset($_SERVER['HTTPS'])) {
        if ('on' == strtolower($_SERVER['HTTPS']) ||
            '1' == $_SERVER['HTTPS']) {
            return true;
        }
    } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
        return true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            return true;
        }
    }
    return false;
}
