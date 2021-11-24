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
        if (php_sapi_name() == 'cli') {
            echo $input;
        } else {
            echo "<pre>" . $input . "</pre>";
        }
    }
}
