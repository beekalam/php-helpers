<?php
class Arr{
    public static function build_array($r, $c, $default = 0)
    {
        if ($r == 0)
            throw new InvalidArgumentException("r cannot be empty");
        $arr = range(0, $r - 1);
        if ($c > 0) {
            foreach ($arr as &$row) {
                $row = array_fill(0, $c, $default);
            }
        }
        return $arr;
    }


}
