<?php

if (!function_exists("tomorrow_unix")) {
    function tomorrow_unix()
    {
        $d        = date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string("1 days"));
        $tomorrow = $d->format('Y-m-d');
        return strtotime($tomorrow);
        // pr($tomorrow);
    }
}

if (!function_exists("yesterday")) {
    function yesterday($str)
    {
        $yesterday = date_sub(date_create(str_replace("/", "-", $str)), date_interval_create_from_date_string("1 days"));
        return $yesterday->format("Y-m-d");
    }
}


if (!function_exists("tomorrow")) {
    function tomorrow($str)
    {
        $yesterday = date_add(date_create(str_replace("/", "-", $str)), date_interval_create_from_date_string("1 days"));
        return $yesterday->format("Y-m-d");
    }
}

if (!function_exists("age")) {
    // Requires PHP >= 5.3
    function age($birthdate)
    {
        return date_create($birthdate)->diff(date_create("today"))->y;
    }
}

if (!function_exists("time2str")) {
    function time2str($time)
    {
        $time = floor($time);
        $str  = '';
        if (!$time)
            return "0 seconds";
        $d = $time / 86400;
        $d = floor($d);
        if ($d) {
            $str  .= "$d days, ";
            $time = $time % 86400;
        }
        $h = $time / 3600;
        $h = floor($h);
        if ($h) {
            $str  .= "$h hours, ";
            $time = $time % 3600;
        }
        $m = $time / 60;
        $m = floor($m);
        if ($m) {
            $str  .= "$m minutes, ";
            $time = $time % 60;
        }
        if ($time)
            $str .= "$time seconds, ";
        $str = preg_replace("/, $/", '', $str);

        return $str;
    }
}

if (!function_exists("fa_time2str")) {
    function fa_time2str($time)
    {
        $str = time2str($time);
        $str = preg_replace("/minutes/", "دقیقه", $str);
        $str = preg_replace("/hours/", "ساعت", $str);
        $str = preg_replace("/seconds/", "ثانیه", $str);

        return $str;
    }
}


if (!function_exists("time2strclock")) {
    function time2strclock($time)
    {
        $time = floor($time);
        if (!$time)
            return "00:00:00";

        $str["hour"] = $str["min"] = $str["sec"] = "00";
        $h           = $time / 3600;
        $h           = floor($h);
        if ($h) {
            if ($h < 10)
                $h = "0" . $h;
            $str["hour"] = "$h";
            $time        = $time % 3600;
        }
        $m = $time / 60;
        $m = floor($m);
        if ($m) {
            if ($m < 10)
                $m = "0" . $m;
            $str["min"] = "$m";
            $time       = $time % 60;
        }
        if ($time) {
            if ($time < 10)
                $time = "0" . $time;
        } else
            $time = "00";
        $str["sec"] = "$time";
        $ret        = "$str[hour]:$str[min]:$str[sec]";

        return $ret;
    }
}

if (!function_exists("date2timediv")) {
    function date2timediv($date, $now = null)
    {
        list($day, $time) = explode(' ', $date);
        $day    = explode('-', $day);
        $time   = explode(':', $time);
        $timest = mktime($time[0], $time[1], $time[2], $day[1], $day[2], $day[0]);
        if (!$now)
            $now = time();
        return ($now - $timest);
    }
}

if (!function_exists("date2time")) {
    function date2time($date)
    {
        list($day, $time) = explode(' ', $date);
        $day    = explode('-', $day);
        $time   = explode(':', $time);
        $timest = mktime($time[0], $time[1], $time[2], $day[1], $day[2], $day[0]);
        return $timest;
    }
}

if (!function_exists("jalali_diff")) {


    /**
     * @param $start Jalali date with '-' separators
     * @param $end  Jalali date with '-' separators
     * @return DateInterval empty DateInterval on bad input.
     */
    function jalali_diff($start, $end)
    {
        $startDate    = explode('-', substr($start, 0, 10));
        $okToContinue = isset($startDate[0]) && isset($startDate[1]) && isset($startDate[2]);
        if ($okToContinue) {
            $startGregorian = jalali_to_gregorian($startDate[0], $startDate[1], $startDate[2]);
            $start          = join("-", $startGregorian) . substr($start, 10);
        }

        $endDate      = explode('-', substr($end, 0, 10));
        $okToContinue = $okToContinue && (isset($endDate[0]) && isset($endDate[1]) && isset($endDate[2]));
        if ($okToContinue) {
            $endGregorian = jalali_to_gregorian($endDate[0], $endDate[1], $endDate[2]);
            $end          = join("-", $endGregorian) . substr($end, 10);
        }

        if ($okToContinue) {
            $startDate = new DateTime($start);
            $endDate   = new DateTime($end);

            $interval = $startDate->diff($endDate);
            return $interval;
        }

        //returns empty DateInterval when inputs are not correct
        return new DateInterval("PT1S");
    }

}

if (!function_exists("bytes2str")) {
    function bytes2str($bytes)
    {
        $bytes = floor($bytes);
        if ($bytes > 536870912)
            $str = sprintf("%5.2f GBs", $bytes / 1073741824);
        else if ($bytes > 524288)
            $str = sprintf("%5.2f MBs", $bytes / 1048576);
        else
            $str = sprintf("%5.2f KBs", $bytes / 1024);

        return $str;
    }
}




