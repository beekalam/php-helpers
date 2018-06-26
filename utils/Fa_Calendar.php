<?php
include "d:/xampp/htdocs/zcomposer/vendor/autoload.php";
include BASE . "/third_party/jdf.php";


class Fa_Calendar
{
    public $start_day = 'sunday';
    public $template  = '';

    /**
     * Replacements array for template
     *
     * @var array
     */
    public $replacements = array();
    public $month_type   = 'long';

    /**
     * How to display names of days
     *
     * @var string
     */
    public $day_type = 'abr';

    /**
     * Whether to show next/prev month links
     *
     * @var bool
     */
    public $show_next_prev = FALSE;

    /**
     * Url base to use for next/prev month links
     *
     * @var bool
     */
    public $next_prev_url = '';

    /**
     * Show days of other months
     *
     * @var bool
     */
    public $show_other_days = FALSE;

    public $str           = '';
    public $adjusted_date = array();
    public $dbg           = '';

    /**
     * Fa_Calendar constructor.
     */
    public function __construct($year, $month)
    {
        $this->str = $this->generate($year, $month);
    }

    public function gen()
    {
        return $this->str;
    }

    public function generate($year = '', $month = '', $data = array())
    {

        $invalid_year_month = $year !== '' && $month !== '' && jcheckdate($month, 1, $year) === false;
        if ($invalid_year_month) {
            $adjusted_date = $this->adjust_date($month, $year);
            $month         = $adjusted_date['month'];
            $year          = $adjusted_date['year'];
        }
//        dump(compact("month","year"));exit;

        $local_time = time();
        if (empty($year)) {
            $year = tr_num(jdate('Y', $local_time), 'en');
        }

        if (empty($month)) {
            $month = tr_num(jdate('m', $local_time), 'en');
        } elseif (strlen($month) === 1) {
            $month = '0' . $month;
        }

        $adjusted_date = $this->adjust_date($month, $year);
        $month         = $adjusted_date['month'];
        $year          = $adjusted_date['year'];
//        dump(compact("month","year")); exit;
//        $gdate = jalali_to_gregorian($year,$month,1);
//        $gdate = sprintf('%s-%02d-%02d',$gdate[0],$gdate[1],$gdate[2]);

//        $total_days = $this->get_total_days($month,$year);
        //determine the total days
        $total_days = tr_num(jdate('t', jmktime(0, 0, 0, $month, 1, $year)), 'en');
        dump(compact("total_days"));
        // Set the starting day of the week
        $start_days = array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);
        $start_day  = isset($start_days[$this->start_day]) ? $start_days[$this->start_day] : 0;
        dump(compact("start_day"));

        // set the starting day number
        $local_date = tr_num(jmktime(12, 0, 0, $month, 1, $year), 'en');
        $date       = tr_num(jgetdate($local_date), 'en');
        $wday       = $date['wday'];
        $day        = $start_day + 1 - $date['wday'];


        $cur_year  = tr_num(jdate('Y', $local_time));
        $cur_month = tr_num(jdate('m', $local_time));
        $cur_day   = tr_num(jdate('j', $local_time));

        $is_current_month = ($cur_year == $year && $cur_month == $month);


        // Generate the template data array
        $this->parse_template();


        // Begin building the calendar output
        $out = $this->replacements['table_open'] . "\n\n" . $this->replacements['heading_row_start'] . "\n";

        // "previous" month link
        if ($this->show_next_prev === TRUE) {
            // Add a trailing slash to the URL if needed
            $this->next_prev_url = preg_replace('/(.+?)\/*$/', '\\1/', $this->next_prev_url);

            $adjusted_date = $this->adjust_date($month - 1, $year);
            $out           .= str_replace('{previous_url}', $this->next_prev_url . $adjusted_date['year'] . '/' . $adjusted_date['month'], $this->replacements['heading_previous_cell']) . "\n";
        }

        // Heading containing the month/year
        $colspan = ($this->show_next_prev === TRUE) ? 5 : 7;

        $this->replacements['heading_title_cell'] = str_replace('{colspan}', $colspan,
            str_replace('{heading}', $this->get_month_name($month) . '&nbsp;' . $year, $this->replacements['heading_title_cell']));

        $out .= $this->replacements['heading_title_cell'] . "\n";

        // "next" month link
        if ($this->show_next_prev === TRUE) {
            $adjusted_date = $this->adjust_date($month + 1, $year);
            $out           .= str_replace('{next_url}', $this->next_prev_url . $adjusted_date['year'] . '/' . $adjusted_date['month'], $this->replacements['heading_next_cell']);
        }

        $out .= "\n" . $this->replacements['heading_row_end'] . "\n\n"
            // Write the cells containing the days of the week
            . $this->replacements['week_row_start'] . "\n";

        $day_names = $this->get_day_names();

        for ($i = 0; $i < 7; $i++) {
            $out .= str_replace('{week_day}', $day_names[($start_day + $i) % 7], $this->replacements['week_day_cell']);
        }

        $out .= "\n" . $this->replacements['week_row_end'] . "\n";

        // Build the main body of the calendar
        while ($day <= $total_days) {
            $out .= "\n" . $this->replacements['cal_row_start'] . "\n";

            for ($i = 0; $i < 7; $i++) {
                if ($day > 0 && $day <= $total_days) {
                    $out .= ($is_current_month === TRUE && $day == $cur_day) ? $this->replacements['cal_cell_start_today'] : $this->replacements['cal_cell_start'];

                    if (isset($data[$day])) {
                        // Cells with content
                        $temp = ($is_current_month === TRUE && $day == $cur_day) ?
                            $this->replacements['cal_cell_content_today'] : $this->replacements['cal_cell_content'];
                        $out  .= str_replace(array('{content}', '{day}'), array($data[$day], $day), $temp);
                    } else {
                        // Cells with no content
                        $temp = ($is_current_month === TRUE && $day == $cur_day) ?
                            $this->replacements['cal_cell_no_content_today'] : $this->replacements['cal_cell_no_content'];
                        $out  .= str_replace('{day}', $day, $temp);
                    }

                    $out .= ($is_current_month === TRUE && $day == $cur_day) ? $this->replacements['cal_cell_end_today'] : $this->replacements['cal_cell_end'];
                } elseif ($this->show_other_days === TRUE) {
                    $out .= $this->replacements['cal_cell_start_other'];

                    if ($day <= 0) {
                        // Day of previous month
                        $prev_month      = $this->adjust_date($month - 1, $year);
                        $prev_month_days = $this->get_total_days($prev_month['month'], $prev_month['year']);
                        $out             .= str_replace('{day}', $prev_month_days + $day, $this->replacements['cal_cell_other']);
                    } else {
                        // Day of next month
                        $out .= str_replace('{day}', $day - $total_days, $this->replacements['cal_cell_other']);
                    }

                    $out .= $this->replacements['cal_cell_end_other'];
                } else {
                    // Blank cells
                    $out .= $this->replacements['cal_cell_start'] . $this->replacements['cal_cell_blank'] . $this->replacements['cal_cell_end'];
                }

                $day++;
            }

            $out .= "\n" . $this->replacements['cal_row_end'] . "\n";
        }

        $out .= "\n" . $this->replacements['table_close'];


        $out .= <<<HTML
   
        <table class="table table-responsive table-striped">
        <thead>
        <th>شنبه</th>
        <th>یکشنبه</th>
        <th>دوشنبه</th>
        <th>سه شنبه</th>
        <th>چهار شنبه</th>
        <th>پنج شنبه</th>
        <th>جمعه</th>
        </thead>
        <tbody>
        

HTML;
//        $start_row = function($i){
//            static $f = 0;
//            $ret = false;
//            if($f == 0)   $ret = true;
//            if($f == 6)  $f = 0;
//            $f++;
//
//            return $ret;
//        };

        for ($i = 0, $j = 1; $i < 35; $i++) {
            $start_row = $i == 0 || $i == 7 || $i == 14 || $i == 21 || $i == 28;
            if ($start_row) $out .= "<tr>";
            if ($i < $wday) {
                $out .= "<td>-</td>";
            } else if (($i + 1 - $wday) > $total_days) {
                $out .= "<td>-</td>";
                $j++;
            } else {
                $out .= "<td>" . (($i + 1) - $wday) . "</td>";
            }

            $end_row = $i == 6 || $i == 13 || $i == 20 || $i == 27 || $i == 34;
            if ($end_row)
                $out .= "</tr>";
        }
        $out .= <<<HTML
    </tbody>
</thead>
</table>
HTML;
        dump(compact("year", "month", "adjusted_date", "total_days", "local_date", "cur_year", "cur_month", "cur_day", "day"));
        return $out;

    }


    public function get_total_days($month, $year)
    {
        if ($month < 1 OR $month > 12) {
            return 0;
        }

    }

    public function adjust_date($month, $year)
    {
        $date          = array();
        $date['month'] = $month;
        $date['year']  = $year;

        while ($date['month'] > 12) {
            $date['month'] -= 12;
            $date['year']++;
        }

        while ($date['month'] <= 0) {
            $date['month'] += 12;
            $date['year']--;
        }

        if (strlen($date['month'] === 1)) {
            $date['month'] = '0' . $date['month'];
        }

        return $date;
    }


    public function parse_template()
    {
        $this->replacements = $this->default_template();

        if (empty($this->template)) {
            return $this;
        }

        if (is_string($this->template)) {
            $today = array('cal_cell_start_today', 'cal_cell_content_today', 'cal_cell_no_content_today', 'cal_cell_end_today');

            foreach (array('table_open', 'table_close', 'heading_row_start', 'heading_previous_cell', 'heading_title_cell', 'heading_next_cell', 'heading_row_end', 'week_row_start', 'week_day_cell', 'week_row_end', 'cal_row_start', 'cal_cell_start', 'cal_cell_content', 'cal_cell_no_content', 'cal_cell_blank', 'cal_cell_end', 'cal_row_end', 'cal_cell_start_today', 'cal_cell_content_today', 'cal_cell_no_content_today', 'cal_cell_end_today', 'cal_cell_start_other', 'cal_cell_other', 'cal_cell_end_other') as $val) {
                if (preg_match('/\{' . $val . '\}(.*?)\{\/' . $val . '\}/si', $this->template, $match)) {
                    $this->replacements[$val] = $match[1];
                } elseif (in_array($val, $today, TRUE)) {
                    $this->replacements[$val] = $this->replacements[substr($val, 0, -6)];
                }
            }
        } elseif (is_array($this->template)) {
            $this->replacements = array_merge($this->replacements, $this->template);
        }

        return $this;
    }

    public function default_template()
    {
        return array(
            'table_open'                => '<table border="0" cellpadding="4" cellspacing="0">',
            'heading_row_start'         => '<tr>',
            'heading_previous_cell'     => '<th><a href="{previous_url}">&lt;&lt;</a></th>',
            'heading_title_cell'        => '<th colspan="{colspan}">{heading}</th>',
            'heading_next_cell'         => '<th><a href="{next_url}">&gt;&gt;</a></th>',
            'heading_row_end'           => '</tr>',
            'week_row_start'            => '<tr>',
            'week_day_cell'             => '<td>{week_day}</td>',
            'week_row_end'              => '</tr>',
            'cal_row_start'             => '<tr>',
            'cal_cell_start'            => '<td>',
            'cal_cell_start_today'      => '<td>',
            'cal_cell_start_other'      => '<td style="color: #666;">',
            'cal_cell_content'          => '<a href="{content}">{day}</a>',
            'cal_cell_content_today'    => '<a href="{content}"><strong>{day}</strong></a>',
            'cal_cell_no_content'       => '{day}',
            'cal_cell_no_content_today' => '<strong>{day}</strong>',
            'cal_cell_blank'            => '&nbsp;',
            'cal_cell_other'            => '{day}',
            'cal_cell_end'              => '</td>',
            'cal_cell_end_today'        => '</td>',
            'cal_cell_end_other'        => '</td>',
            'cal_row_end'               => '</tr>',
            'table_close'               => '</table>'
        );
    }

    public function get_month_name($month)
    {
        if ($this->month_type === 'short') {
            $month_names = array('01' => 'cal_jan', '02' => 'cal_feb', '03' => 'cal_mar', '04' => 'cal_apr', '05' => 'cal_may', '06' => 'cal_jun', '07' => 'cal_jul', '08' => 'cal_aug', '09' => 'cal_sep', '10' => 'cal_oct', '11' => 'cal_nov', '12' => 'cal_dec');
        } else {
            $month_names = array('01' => 'cal_january', '02' => 'cal_february', '03' => 'cal_march', '04' => 'cal_april', '05' => 'cal_mayl', '06' => 'cal_june', '07' => 'cal_july', '08' => 'cal_august', '09' => 'cal_september', '10' => 'cal_october', '11' => 'cal_november', '12' => 'cal_december');
        }
        return ucfirst(substr($month_names[$month], 4));
//        return ($this->CI->lang->line($month_names[$month]) === FALSE)
//            ? ucfirst(substr($month_names[$month], 4))
//            : $this->CI->lang->line($month_names[$month]);
    }

    // --------------------------------------------------------------------

    /**
     * Get Day Names
     *
     * Returns an array of day names (Sunday, Monday, etc.) based
     * on the type. Options: long, short, abr
     *
     * @param    string
     * @return    array
     */
    public function get_day_names($day_type = '')
    {
        if ($day_type !== '') {
            $this->day_type = $day_type;
        }

        if ($this->day_type === 'long') {
            $day_names = array('شنبه', 'یکشنبه', 'دوشنبه', 'سه شنبه', 'thursday', 'friday', 'saturday');
        } elseif ($this->day_type === 'short') {
            $day_names = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
        } else {
            $day_names = array('su', 'mo', 'tu', 'we', 'th', 'fr', 'sa');
        }

        $days = array();
        for ($i = 0, $c = count($day_names); $i < $c; $i++) {
            $days[] = ucfirst($day_names[$i]);
        }

        return $days;
    }


}


function build_next_month($month, $year)
{
    $month++;

    if ($month > 12) {
        $year++;
        $month = 1;
    }
    return "http://localhost/webutils/calendar.php?" . http_build_query(compact('year', 'month'));
}

function build_prev_month($month, $year)
{
    $month--;
    if ($month <= 0) {
        $year--;
        $month = 12;
    }

    return "http://localhost/webutils/calendar.php?" . http_build_query(compact('year', 'month'));
}

function build_links($month, $year)
{
    echo "<a href='" . build_next_month($month, $year) . "'>next</a>";
    echo "<hr/>";
    echo "<a href='" . build_prev_month($month, $year) . "'>prev</a>";
}

