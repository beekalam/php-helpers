<?php

namespace Beekalam\PHPHelpers;

class Debug
{
    public static function dd($input)
    {
        self::dump($input);
        die();
    }

    public static function dump($input)
    {
        if (is_array($input) || is_object($input)) {
            $input = print_r($input, true);
        }
        echo $input;
    }
}