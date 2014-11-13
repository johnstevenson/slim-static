<?php
namespace Statical\SlimStatic\Tests;

class Utils
{
    public static function setEnvironvment()
    {
        $_SERVER['DOCUMENT_ROOT'] = '/var/www';
        $_SERVER['SCRIPT_FILENAME'] = '/var/www/foo/index.php';
        $_SERVER['SERVER_NAME'] = 'slim';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['SCRIPT_NAME'] = '/foo/index.php';
        $_SERVER['REQUEST_URI'] = '/foo/index.php/bar/xyz';
        $_SERVER['PATH_INFO'] = '/bar/xyz';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = 'one=1&two=2&three=3';
        $_SERVER['HTTPS'] = '';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        unset($_SERVER['CONTENT_TYPE'], $_SERVER['CONTENT_LENGTH']);
    }
}
