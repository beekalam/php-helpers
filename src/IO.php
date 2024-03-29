<?php

namespace Beekalam\PHPHelpers;
class IO{
    public static function stdin_stream()
    {
        while($line = fgets(STDIN))
        {
            yield $line;
        }
    }

    public static function read_int_array()
    {
        $str = fgets(STDIN);
        $arr = explode(" ", $str);
        $arr = array_map(function ($in) {
            return (int)$in;
        }, $arr);
        return $arr;
    }

    public static function readline()
    {
        return trim(fgets(STDIN));
    }

    public static function read_int()
    {
        $str = trim(fgets(STDIN));
        return (int)$str;
    }
}
