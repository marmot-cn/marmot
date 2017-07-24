<?php

class Server
{
    public static function host() : string
    {
        return $_SERVER['HTTP_HOST'];
    }
}
