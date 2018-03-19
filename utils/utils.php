<?php

function pr($in)
{
	echo "<pre>";
	echo print_r($in,true);
	echo "</pre>";
}
function pre($in){
	pr($in);
	exit;
}

/**
 * determines if a passed string matches the criteria for a Sugar GUID
 * @param string $guid
 * @return bool False on failure
 */
function is_guid($guid) {
	if(strlen($guid) != 36) {
		return false;
	}

	if(preg_match("/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/i", $guid)) {
		return true;
	}

	return true;;
}



/**
 * A temporary method of generating GUIDs of the correct format for our DB.
 * @return String contianing a GUID in the format: aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
 *
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function create_guid()
{
	$microTime = microtime();
	list($a_dec, $a_sec) = explode(" ", $microTime);

	$dec_hex = dechex($a_dec* 1000000);
	$sec_hex = dechex($a_sec);

	ensure_length($dec_hex, 5);
	ensure_length($sec_hex, 6);

	$guid = "";
	$guid .= $dec_hex;
	$guid .= create_guid_section(3);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= $sec_hex;
	$guid .= create_guid_section(6);

	return $guid;

}

function create_guid_section($characters)
{
	$return = "";
	for($i=0; $i<$characters; $i++)
	{
		$return .= dechex(mt_rand(0,15));
	}
	return $return;
}

function ensure_length(&$string, $length)
{
	$strlen = strlen($string);
	if($strlen < $length)
	{
		$string = str_pad($string,$length,"0");
	}
	else if($strlen > $length)
	{
		$string = substr($string, 0, $length);
	}
}

function microtime_diff($a, $b) {
	list($a_dec, $a_sec) = explode(" ", $a);
	list($b_dec, $b_sec) = explode(" ", $b);
	return $b_sec - $a_sec + $b_dec - $a_dec;
}

function add_http($url) {
	if(!preg_match("@://@i", $url)) {
		$scheme = "http";
		if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
			$scheme = 'https';
		}

		return "{$scheme}://{$url}";
	}

	return $url;
}

// Returns TRUE if $str begins with $begin
function str_begin($str, $begin) {
	return (substr($str, 0, strlen($begin)) == $begin);
}

// Returns TRUE if $str ends with $end
function str_end($str, $end) {
	return (substr($str, strlen($str) - strlen($end)) == $end);
}

function values_to_keys($array)
{
	$new_array = array();
	if(!is_array($array))
	{
		return $new_array;
	}
	foreach($array as $arr){
		$new_array[$arr] = $arr;
	}
	return $new_array;
}

/**
 * Check to see if the number is empty or non-zero
 * @param $value
 * @return boolean
 **/
function number_empty($value)
{
	return empty($value) && $value != '0';
}

function get_current_url()
{
	$href = "http:";
	if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
	{
		$href = 'https:';
	}

	$href.= "//".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'];
	return $href;
}


function br2nl($str) {
	$regex = "#<[^>]+br.+?>#i";
	preg_match_all($regex, $str, $matches);

	foreach($matches[0] as $match) {
		$str = str_replace($match, "<br>", $str);
	}

	$brs = array('<br>','<br/>', '<br />');
	$str = str_replace("\r\n", "\n", $str); // make from windows-returns, *nix-returns
	$str = str_replace("\n\r", "\n", $str); // make from windows-returns, *nix-returns
	$str = str_replace("\r", "\n", $str); // make from windows-returns, *nix-returns
	$str = str_ireplace($brs, "\n", $str); // to retrieve it

	return $str;
}



/**
 * Adds the href HTML tags around any URL in the $string
 */
function url2html($string) {
	//
	$return_string = preg_replace('/(\w+:\/\/)(\S+)/', ' <a href="\\1\\2" target="_new"  style="font-weight: normal;">\\1\\2</a>', $string);
	return $return_string;
}
// End customization by Julian

/**
 * tries to determine whether the Host machine is a Windows machine
 */
function is_windows() {
    static $is_windows = null;
    if (!isset($is_windows)) {
        $is_windows = strtoupper(substr(PHP_OS, 0, 3)) == 'WIN';
    }
    return $is_windows;
}


/**
 * This function returns an array of phpinfo() results that can be parsed and
 * used to figure out what version we run, what modules are compiled in, etc.
 * @param	$level			int		info level constant (1,2,4,8...64);
 * @return	$returnInfo		array	array of info about the PHP environment
 * @author	original by "code at adspeed dot com" Fron php.net
 * @author	customized for Sugar by Chris N.
 */
function get_php_info($level=-1) {
	/**	Name (constant)		Value	Description
		INFO_GENERAL		1		The configuration line, php.ini location, build date, Web Server, System and more.
		INFO_CREDITS		2		PHP Credits. See also phpcredits().
		INFO_CONFIGURATION	4		Current Local and Master values for PHP directives. See also ini_get().
		INFO_MODULES		8		Loaded modules and their respective settings. See also get_loaded_extensions().
		INFO_ENVIRONMENT	16		Environment Variable information that's also available in $_ENV.
		INFO_VARIABLES		32		Shows all predefined variables from EGPCS (Environment, GET, POST, Cookie, Server).
		INFO_LICENSE		64		PHP License information. See also the license FAQ.
		INFO_ALL			-1		Shows all of the above. This is the default value.
	 */
	ob_start();
	phpinfo($level);
	$phpinfo = ob_get_contents();
	ob_end_clean();

	$phpinfo	= strip_tags($phpinfo,'<h1><h2><th><td>');
	$phpinfo	= preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$phpinfo);
	$phpinfo	= preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$phpinfo);
	$parsedInfo	= preg_split('/(<h.?>[^<]+<\/h.>)/', $phpinfo, -1, PREG_SPLIT_DELIM_CAPTURE);
	$match		= '';
	$version	= '';
	$returnInfo	= array();

	if(preg_match('/<h1 class\=\"p\">PHP Version ([^<]+)<\/h1>/', $phpinfo, $version)) {
		$returnInfo['PHP Version'] = $version[1];
	}


	for ($i=1; $i<count($parsedInfo); $i++) {
		if (preg_match('/<h.>([^<]+)<\/h.>/', $parsedInfo[$i], $match)) {
			$vName = trim($match[1]);
			$parsedInfo2 = explode("\n",$parsedInfo[$i+1]);

			foreach ($parsedInfo2 AS $vOne) {
				$vPat	= '<info>([^<]+)<\/info>';
				$vPat3	= "/$vPat\s*$vPat\s*$vPat/";
				$vPat2	= "/$vPat\s*$vPat/";

				if (preg_match($vPat3,$vOne,$match)) { // 3cols
					$returnInfo[$vName][trim($match[1])] = array(trim($match[2]),trim($match[3]));
				} elseif (preg_match($vPat2,$vOne,$match)) { // 2cols
					$returnInfo[$vName][trim($match[1])] = trim($match[2]);
				}
			}
		} elseif(true) {

		}
	}

	return $returnInfo;
}

/**
 * This function will take a string that has tokens like {0}, {1} and will replace
 * those tokens with the args provided
 * @param	$format string to format
 * @param	$args args to replace
 * @return	$result a formatted string
 */
function string_format($format, $args){
	$result = $format;

    /** Bug47277 fix.
     * If args array has only one argument, and it's empty, so empty single quotes are used '' . That's because
     * IN () fails and IN ('') works.
     */
    if (count($args) == 1)
    {
        reset($args);
        $singleArgument = current($args);
        if (empty($singleArgument))
        {
            return str_replace("{0}", "''", $result);
        }
    }
    /* End of fix */

	for($i = 0; $i < count($args); $i++){
		$result = str_replace('{'.$i.'}', $args[$i], $result);
	}
	return $result;
}


// works nicely with array_map() -- can be used to wrap single quotes around each element in an array
function add_squotes($str) {
	return "'" . $str . "'";
}


function var_to_string($var)
{
	if (is_object($var)) {
		return sprintf('Object(%s)', get_class($var));
	}

	if (is_array($var)) {
		$a = array();
		foreach ($var as $k => $v) {
			$a[] = sprintf('%s => %s', $k, var_to_string($v));
		}

		return sprintf('Array(%s)', implode(', ', $a));
	}

	if (is_resource($var)) {
		return sprintf('Resource(%s)', get_resource_type($var));
	}

	if (null === $var) {
		return 'null';
	}

	if (false === $var) {
		return 'false';
	}

	if (true === $var) {
		return 'true';
	}

	return (string) $var;
}


/**
 * Formats $value to the given length and appends SI prefixes
 * with a $length of 0 no truncation occurs, number is only formatted
 * to the current locale
 *
 * examples:
 * <code>
 * echo formatNumber(123456789, 6);     // 123,457 k
 * echo formatNumber(-123456789, 4, 2); //    -123.46 M
 * echo formatNumber(-0.003, 6);        //      -3 m
 * echo formatNumber(0.003, 3, 3);      //       0.003
 * echo formatNumber(0.00003, 3, 2);    //       0.03 m
 * echo formatNumber(0, 6);             //       0
 * </code>
 *
 * @param double  $value          the value to format
 * @param integer $digits_left    number of digits left of the comma
 * @param integer $digits_right   number of digits right of the comma
 * @param boolean $only_down      do not reformat numbers below 1
 * @param boolean $noTrailingZero removes trailing zeros right of the comma
 *                                (default: true)
 *
 * @return string   the formatted value and its unit
 *
 * @access  public
 */
function format_number(
	$value,
	$digits_left = 3,
	$digits_right = 0,
	$only_down = false,
	$noTrailingZero = true
) {
	if ($value == 0) {
		return '0';
	}

	$originalValue = $value;
	//number_format is not multibyte safe, str_replace is safe
	if ($digits_left === 0) {
		$value = number_format(
			$value,
			$digits_right,
			/* l10n: Decimal separator */
			__('.'),
			/* l10n: Thousands separator */
			__(',')
		);
		if (($originalValue != 0) && (floatval($value) == 0)) {
			$value = ' <' . (1 / pow(10, $digits_right));
		}
		return $value;
	}

	// this units needs no translation, ISO
	$units = array(
		-8 => 'y',
		-7 => 'z',
		-6 => 'a',
		-5 => 'f',
		-4 => 'p',
		-3 => 'n',
		-2 => '&micro;',
		-1 => 'm',
		0 => ' ',
		1 => 'k',
		2 => 'M',
		3 => 'G',
		4 => 'T',
		5 => 'P',
		6 => 'E',
		7 => 'Z',
		8 => 'Y'
	);
	/* l10n: Decimal separator */
	$decimal_sep = __('.');
	/* l10n: Thousands separator */
	$thousands_sep = __(',');

	// check for negative value to retain sign
	if ($value < 0) {
		$sign = '-';
		$value = abs($value);
	} else {
		$sign = '';
	}

	$dh = pow(10, $digits_right);

	/*
	 * This gives us the right SI prefix already,
	 * but $digits_left parameter not incorporated
	 */
	$d = floor(log10($value) / 3);
	/*
	 * Lowering the SI prefix by 1 gives us an additional 3 zeros
	 * So if we have 3,6,9,12.. free digits ($digits_left - $cur_digits)
	 * to use, then lower the SI prefix
	 */
	$cur_digits = floor(log10($value / pow(1000, $d))+1);
	if ($digits_left > $cur_digits) {
		$d -= floor(($digits_left - $cur_digits)/3);
	}

	if ($d < 0 && $only_down) {
		$d = 0;
	}

	$value = round($value / (pow(1000, $d) / $dh)) /$dh;
	$unit = $units[$d];

	// number_format is not multibyte safe, str_replace is safe
	$formattedValue = number_format(
		$value,
		$digits_right,
		$decimal_sep,
		$thousands_sep
	);
	// If we don't want any zeros, remove them now
	if ($noTrailingZero && strpos($formattedValue, $decimal_sep) !== false) {
		$formattedValue = preg_replace('/' . preg_quote($decimal_sep) . '?0+$/', '', $formattedValue);
	}

	if ($originalValue != 0 && floatval($value) == 0) {
		return ' <' . number_format(
			(1 / pow(10, $digits_right)),
			$digits_right,
			$decimal_sep,
			$thousands_sep
		)
		. ' ' . $unit;
	}

	return $sign . $formattedValue . ' ' . $unit;
} // end of the 'formatNumber' function


/**
 * Returns the number of bytes when a formatted size is given
 *
 * @param string $formatted_size the size expression (for example 8MB)
 *
 * @return integer  The numerical part of the expression (for example 8)
 */
function extractValueFromFormattedSize($formatted_size)
{
	$return_value = -1;

	if (preg_match('/^[0-9]+GB$/', $formatted_size)) {
		$return_value = mb_substr($formatted_size, 0, -2)
			* pow(1024, 3);
	} elseif (preg_match('/^[0-9]+MB$/', $formatted_size)) {
		$return_value = mb_substr($formatted_size, 0, -2)
			* pow(1024, 2);
	} elseif (preg_match('/^[0-9]+K$/', $formatted_size)) {
		$return_value = mb_substr($formatted_size, 0, -1)
			* pow(1024, 1);
	}
	return $return_value;
}// end of the 'extractValueFromFormattedSize' function
/**
 * Returns a given timespan value in a readable format.
 *
 * @param int $seconds the timespan
 *
 * @return string  the formatted value
 */
function timespan_format($seconds)
{
	$days = floor($seconds / 86400);
	if ($days > 0) {
		$seconds -= $days * 86400;
	}

	$hours = floor($seconds / 3600);
	if ($days > 0 || $hours > 0) {
		$seconds -= $hours * 3600;
	}

	$minutes = floor($seconds / 60);
	if ($days > 0 || $hours > 0 || $minutes > 0) {
		$seconds -= $minutes * 60;
	}

	return sprintf(
		'%s days, %s hours, %s minutes and %s seconds',
		(string)$days,
		(string)$hours,
		(string)$minutes,
		(string)$seconds
	);
}
function divide($array)
{
	return array(array_keys($array), array_values($array));
}

/**
 * Flatten a multi-dimensional associative array with dots.
 *
 * @param  array   $array
 * @param  string  $prepend
 * @return array
 */
function dot($array, $prepend = '')
{
	$results = [];

	foreach ($array as $key => $value) {
		if (is_array($value) && ! empty($value)) {
			$results = array_merge($results, dot($value, $prepend.$key.'.'));
		} else {
			$results[$prepend.$key] = $value;
		}
	}

	return $results;
}
/**
 * Return the first element in an array passing a given truth test.
 *
 * @param  array  $array
 * @param  callable|null  $callback
 * @param  mixed  $default
 * @return mixed
 */
function first($array, callable $callback = null, $default = null)
{
	if (is_null($callback)) {
		if (empty($array)) {
			return value($default);
		}

		foreach ($array as $item) {
			return $item;
		}
	}

	foreach ($array as $key => $value) {
		if (call_user_func($callback, $value, $key)) {
			return $value;
		}
	}

	return value($default);
}


/**
 * Return the last element in an array passing a given truth test.
 *
 * @param  array  $array
 * @param  callable|null  $callback
 * @param  mixed  $default
 * @return mixed
 */
function last($array, callable $callback = null, $default = null)
{
	if (is_null($callback)) {
		return empty($array) ? value($default) : end($array);
	}

	return first(array_reverse($array, true), $callback, $default);
}

/**
 * Determines if an array is associative.
 *
 * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
 *
 * @param  array  $array
 * @return bool
 */
function is_assoc(array $array)
{
	$keys = array_keys($array);

	return array_keys($keys) !== $keys;
}

 /**
 * Filter the array using the given callback.
 *
 * @param  array  $array
 * @param  callable  $callback
 * @return array
 */
function where($array, callable $callback)
{
	return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
}


/**
 * Return true if we are in a context of submitting a parameter
 *
 * @param 	string	$paramname		Name or parameter to test
 * @return 	boolean					True if we have just submit a POST or GET request with the parameter provided (even if param is empty)
 */
function getpost_isset($paramname)
{
	return (isset($_POST[$paramname]) || isset($_GET[$paramname]));
}

/**
 *  Returns text escaped for inclusion into javascript code
 *
 *  @param      string		$stringtoescape		String to escape
 *  @param		int		$mode				0=Escape also ' and " into ', 1=Escape ' but not " for usage into 'string', 2=Escape " but not ' for usage into "string", 3=Escape ' and " with \
 *  @param		int		$noescapebackslashn	0=Escape also \n. 1=Do not escape \n.
 *  @return     string     		 				Escaped string. Both ' and " are escaped into ' if they are escaped.
 */
function escape_js($stringtoescape, $mode=0, $noescapebackslashn=0)
{
	// escape quotes and backslashes, newlines, etc.
	$substitjs=array("&#039;"=>"\\'","\r"=>'\\r');
	//$substitjs['</']='<\/';	// We removed this. Should be useless.
	if (empty($noescapebackslashn)) { $substitjs["\n"]='\\n'; $substitjs['\\']='\\\\'; }
	if (empty($mode)) { $substitjs["'"]="\\'"; $substitjs['"']="\\'"; }
	else if ($mode == 1) $substitjs["'"]="\\'";
	else if ($mode == 2) { $substitjs['"']='\\"'; }
	else if ($mode == 3) { $substitjs["'"]="\\'"; $substitjs['"']="\\\""; }
	return strtr($stringtoescape, $substitjs);
}


/**
 *  Returns text escaped for inclusion in HTML alt or title tags, or into values of HTML input fields.
 *
 *  @param      string		$stringtoescape		String to escape
 *  @param		int			$keepb				1=Preserve b tags (otherwise, remove them)
 *  @param      int         $keepn              1=Preserve \r\n strings (otherwise, replace them with escaped value)
 *  @return     string     				 		Escaped string
 *  @see		dol_string_nohtmltag
 */
function escape_htmltag($stringtoescape, $keepb=0, $keepn=0)
{
	// escape quotes and backslashes, newlines, etc.
	$tmp=html_entity_decode($stringtoescape, ENT_COMPAT, 'UTF-8');		// TODO Use htmlspecialchars_decode instead, that make only required change for html tags
	if (! $keepb) $tmp=strtr($tmp, array("<b>"=>'','</b>'=>''));
	if (! $keepn) $tmp=strtr($tmp, array("\r"=>'\\r',"\n"=>'\\n'));
	return htmlentities($tmp, ENT_COMPAT, 'UTF-8');						// TODO Use htmlspecialchars instead, that make only required change for html tags
}
/**
 * Determine if a PHP function is disabled via ini setting.
 *
 * @param string $function
 * @return bool
 */
function function_enabled($function) {
	return strpos(ini_get("disabled_functions"), $function) === false;
}
/**
 * Get the location of the final mismatched quote in a string.
 *
 * A string has mismatched quotation marks in it if it has an odd number of
 * quotation marks in it. If a string has mismatched quotes then return the
 * position of the mismatched quote in the string. Otherwise, the string has
 * matching quotes in it, so return false.
 *
 * @param $string
 * @param string $quoteCharacter
 * @return int|false
 */
function  mismatched_quote_position($string, $quoteCharacter = "\"") {
	return substr_count($string, $quoteCharacter) % 2 == 0 ? false : strrpos($string, $quoteCharacter);
}

/**
	 * A PHP implementation of escapeshellcmd().
	 *
	 * Some hosts add the escapeshellcmd() function to the disabled_functions
	 * php.ini directive. Perform escapeshellcmd() manually in those cases.
	 *
	 * @see http://www.php.net/manual/en/function.escapeshellcmd.php
	 * @param string $string
	 * @return string
	 */
function escape_shellcmd($string) {
	if (function_exists("escapeshellcmd") && function_enabled("escapeshellcmd")) {
		return escapeshellcmd($string);
	}

	$shellCharacters = array("#", "&", ";", "`", "|", "*", "?", "~", "<", ">", "^", "(", ")", "[", "]", "{", "}", "\$", chr(10), chr(255));

	if (is_windows()) {
		$shellCharacters[] = "%";
		$shellCharacters[] = "\\";
		$string = str_replace($shellCharacters, " ", $string);
		$quotePosition = mismatched_quote_position($string);

		if ($quotePosition !== false) {
			$string = substr_replace($string, " ", $quotePosition, 1);
		}

			$quotePosition = mismatched_quote_position($string, "'");

			if ($quotePosition !== false) {
				$string = substr_replace($string, " ", $quotePosition, 1);
			}
	}
	else {
		$string = str_replace("\\", "\\\\", $string);
		foreach ($shellCharacters as $shellCharacter) {
			$string = str_replace($shellCharacter, "\\" . $shellCharacter, $string);
		}

		$quotePosition = mismatched_quote_position($string);

		if ($quotePosition !== false) {
			$string = substr_replace($string, '\"', $quotePosition, 1);
		}

		$quotePosition = mismatched_quote_position($string, '\'');


		if ($quotePosition !== false) {
			$string = substr_replace($string, "'", $quotePosition, 1);
		}
	}

	return $string;
}

function is_filename_safe($filename) {
		if (empty($filename)) {
			return false;
		}


		if (strpos($filename, "") !== false) {
			return false;
		}


		if (strpos($filename, DIRECTORY_SEPARATOR) !== false || strpos($filename, PATH_SEPARATOR) !== false) {
			return false;
		}


		if (strpos($filename, chr(8)) !== false) {
			return false;
		}


		if (substr($filename, 0, 1) === ".") {
			return false;
		}


		// if (whmcsEscapeshellcmd($filename) != $filename) {
		// 	return false;
		// }

		return true;
	}

//----------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------
function mean($arr){
	return count($arr) ? array_sum($arr)/count($arr) : 0;
}
//----------------------------------------------------------------------------------------------
 define('end_of_line',PHP_EOL);
//define('end_of_line','');
function a($content='',$attrs='')
{
	return "<a {$attrs}>{$content}</a>";
}
function ea($content='',$attrs='')
{
	echo a($content,$attrs);
}

function span($content='',$attrs='')
{
	return "<span>{$content}</span>" . end_of_line;
}

function espan($content='')
{
	echo span($content);
}

function div($content='',$attrs='')
{
	return "<div {$attrs}>{$content}</div>" . end_of_line;
}

function ediv($content='',$attrs='')
{
	echo div($content,$attrs);
}


function lbl($content='',$attrs='')	
{
	return "<label {$attrs}>{$content}</label>" . end_of_line;
}

function elbl($content='',$attrs='')
{
	echo lbl($content,$attrs);
}

function btn($content='', $attrs='')
{
	return "<button ${attrs}>{$content}</button>" . end_of_line;
}

function ebtn($content='',$attrs='')
{
	echo btn($content,$attrs);
}

function txt($content='',$attrs='')
{
	return "<input type='text' value='{$content}' {$attrs} >";
}

function etxt($content='',$attrs='')
{
	echo txt($content,$attrs);
}

function hidden($name='',$value='')
{
	return "<input type='hidden' id='{$name}' name='{$name}' value='{$value}' />" . end_of_line;
}

function ehidden($name='',$value='')
{
	echo hidden($name,$value);
}

function p($content='',$attrs='')
{
	return "<p {$attrs}>{$content}</p>" . end_of_line;
}

function ep($content='',$attrs='')
{
	echo p($content,$attrs);
}

function submit($content='',$attrs='')
{
	return "<input type='submit' value='{$content} {$attrs} />" . end_of_line;
}

function esubmit($content='',$attrs='')
{
	echo submit($content,$attrs);
}

function email($content='',$attrs='')
{
	return "<input type='email' ${attrs}>" . end_of_line;
}

function eemail($content='',$attrs='')
{
	echo email($content,$attrs);
}

function checkbox($content='',$attrs='')
{
	return "<input type='checkbox' {$attrs}>{$content}" . end_of_line;
}

function echeckbox($content='',$attrs='')
{
	echo checkbox($content,$attrs);
}

function radio($content='',$attrs='')
{
	return "<input type='radio' {$attrs}>" . end_of_line;
}

function eradio($content='',$attrs='')
{
	echo radio($content,$attrs);
}

function img($src='',$attrs='')
{
	return "<img src='{$src}' {$attrs} >" . end_of_line;
}

function eimg($src='',$attrs='')
{
	echo img($src,$attrs);
}

function css($url='')
{
	return "<link href='{$url}' rel='stylesheet' />" . end_of_line;
}

function ecss($url='')
{
	echo css($url);
}

function js($url='')
{
	return "<script src='{$url}' type='script/javascript'></script>" . end_of_line;
}

function ejs($url='')
{
	echo js($url);
}


function meta($name='',$description='')
{
	return "<meta name='{$name}' description='{$description} />" . end_of_line;
}

function emeta($name='',$description='')
{
	echo meta($name,$description);
}


function tag($tag,$content='',$attrs='')
{
	return "<{$tag} {$attrs}>{$content}</{$tag}>" . end_of_line;
}

function et($tag,$content='',$attrs='')
{
		echo tag($tag,$content='',$attrs='');
}

///------------------------------------------------
define('use_template',true);
$template = array
(
	"label" 	=> "lbl",
	"lbl" 		=> "lbl",

	"button" 	=> "btn",
	"btn"		=> "btn",

	"a"			=> "a-ex"
);
///------------------------------------------------
function parse_line($args)
{
		global $template;
		$args = explode("|",$args);
		$tag = $classes = $id = $content = $rest = $styles = "";
		foreach($args as $arg)
		{
			if(str_begin($arg,"~"))
			{
				$tag = substr($arg,1);
			}else if(str_begin($arg,"."))
			{
				$classes = trim(join(" ",explode(".",$arg)));
			}else if(str_begin($arg,"@"))
			{
				$styles =  substr($arg,1);
			}else if(str_begin($arg,"#"))
			{
				$id = substr($arg,1);
			}else if(str_begin($arg,"^"))
			{
				$content=substr($arg,1);
			}else{
				$rest .= " " . $arg  ." ";
			}
		}
		
		if(defined('use_template') && use_template)
		{
			if(isset($template[$tag]))
				$classes .=  " " . $template[$tag];
			
		}
		$classes = empty($classes) ? '' :"class='{$classes}'";
		$styles  = empty($styles)  ? '' : "style='{$styles}'";
		$id      = empty($id)      ? '' :"id='{$id}'";
		$attrs   = trim("{$classes} {$id} {$styles} {$rest}") ;

		switch($tag){
			case 'lbl':
			case 'label':
				return lbl($content,$attrs) . end_of_line;
			case 'btn':
			case 'button':
				return btn($content,$attrs) . end_of_line;
			case 'div':
				return div($content,$attrs) . end_of_line;
			case 'a':
				return a($content,$attrs) . end_of_line;
			case 'txt':
				return txt($content,$attrs) . end_of_line;
			case 'img':
				return img($content,$attrs) . end_of_line;
			case 'checkbox':
				return checkbox($content,$attrs) . end_of_line;
			case 'radio':
				return radio($content,$attrs) . end_of_line;
			case 'span':
				return span($content,$attrs) . end_of_line;
			case 'email':
				return email($content,$attrs) . end_of_line;
		}
		return "<{$tag} {$attrs}>{$content}</{$tag}>"; 
}
function e()
{
	$num_args = func_num_args();
	if($num_args==1)
	{
		return parse_line(func_get_arg(0));
	}
	
	if($num_args > 1)
	{
		$i=0;
		$head=$tail="";
		
		for(; $i < $num_args; $i++)
		{
			$arg = func_get_arg($i);
			if(str_begin($arg,"~div"))
			{
				$div = str_replace("</div>","",parse_line($arg) );
				$head .=  $div .  end_of_line . str_repeat(" ",$i+1) ;
				$tail .= "</div>" . end_of_line . str_repeat(" ",$i+1) ;
			}else
				break;
		}
		if($i < $num_args){
			return $head  . e(func_get_arg($i)) . $tail . end_of_line;
		}
	}
	return "there was an error";
}


/*
$i=0;
while($i++<1)
	echo e("~div",
				"~lbl|.label.label-info|#mylabel|data-id='1'|^" ."this is the of the label");
exit;
echo  e("~div",
		 "~div",
		 "~div",
		 "~div",
			"~lbl|.label.label-info|#mylabel|data-id='1'|^" ."this is the of the label" . end_of_line .
				e("~btn|.label.label-info|#mylabel|data-id='1'|^" ."this is the of the label") . end_of_line .
				e("~div|.label.label-info|#mylabel|data-id='1'|^" ."this is the of the label"));
exit;
*/

//---------------------------------------------------------------------------------------------
/**
 * Check if a string is json encoded
 *
 * @param  string $string string to check
 * @return bool
 */
function is_json($string)
{
	json_decode($string);
	return json_last_error() === JSON_ERROR_NONE;
}

/**
 * Check if a string is a valid XML
 *
 * @param  string  $string  string to check
 * @return bool
 * @throws \FuelException
 */
function is_xml($string)
{
	if ( ! defined('LIBXML_COMPACT'))
	{
		throw new \FuelException('libxml is required to use Str::is_xml()');
	}

	$internal_errors = libxml_use_internal_errors();
	libxml_use_internal_errors(true);
	$result = simplexml_load_string($string) !== false;
	libxml_use_internal_errors($internal_errors);

	return $result;
}

/**
 * Check if a string is serialized
 *
 * @param  string  $string  string to check
 * @return bool
 */
 function is_serialized($string)
{
	$array = @unserialize($string);
	return ! ($array === false and $string !== 'b:0;');
}

/**
 * Check if a string is html
 *
 * @param  string $string string to check
 * @return bool
 */
 function is_html($string)
{
	return strlen(strip_tags($string)) < strlen($string);
}

function is_base64($data)
{
    if ( base64_encode(base64_decode($data)) === $data)
    {
        return true;
    }
    return false;
}

function is_valid_md5($md5 ='')
{
    return preg_match('/^[a-f0-9]{32}$/', $md5);
}

function is_valid_sha1($sha='')
{
 	return preg_match('/^[a-fA-F0-9]{40}$/', $sha);   
}

//probably not right
function default_ini_path()
{
    return (realpath($_SERVER['DOCUMENT_ROOT']));
}

function extension_dir()
{
	$extdir = ini_get('extension_dir');
	if ($extdir == './' || ($extdir == '.\\' && is_windows())) {
		$extdir = '.';
	}
	return $extdir;
}

function is_persian_date($in){
	$arr = explode("-",$in);
	$arr2 = explode("/",$in);
	if(count($arr)!==3 && count($arr2)!==3) return false;

	//tests for m-d-y  and 1499 > y > 1300;
	if(count($arr) == 3){
		$m = intval(ltrim($arr[0],'0'));
		$d = intval(ltrim($arr[1],'0'));
		$y = intval($arr[2]);
		$ok = $m >=1 && $m <= 12;
		$ok = $ok && $d >=1 && $d <= 31;
		if($y > 100 && ($y < 1300 || $y > 1499) )
				$ok = false;
	}

	// tests for y-m-d  and 1499 > y > 1300;
	if(count($arr) == 3){
		$y = intval($arr[0]);
		$m = intval(ltrim($arr[1],0));
		$d = intval(ltrim($arr[2],0));
		$ok2 = $d >=1 && $d <=12;
		$ok2 = $ok2 && $m>=1 && $d<=31;
		if($y > 100 && ($y < 1300 || $y > 1499))
				$ok2 = false;	
	}

	if(isset($ok) && isset($ok2)) return $ok || $ok2;

	if(count($arr2)==3){
		$in= str_replace("/","-",$in);
		return is_persian_date($in);
	}
	return false;
}
