<?php
if (!function_exists("gcd")) {
    function gcd($a, $b)
    {
        $m = min($a, $b);
        $gcd = 1;
        for ($i = 1; $i <= $m; $i++) {
            if ($a % $i == 0 && $b % $i == 0)
                $gcd = $i;
        }
        return $gcd;
    }
}