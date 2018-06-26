function number_format(nStr){
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
function format_duration(seconds)
    {
        var seconds=parseInt(seconds);
        var hours=parseInt(seconds/3600);
        
        if(hours<10)
            hours="0" + hours;
        var rest = seconds%3600;
        var mins=parseInt(rest/60);
        
        if(mins<10)
            mins="0" + mins;
        secs=rest%60;
        if(secs<10)
            secs="0" + secs;
        
        return hours + ":" + mins + ":" + secs;
}

function set_height(left, right)
{
        left = $("#" +left);
        right = $("#" + right);
        var height = Math.max(left.height(), right.height());
        left.height(height);
        right.height(height);
}
//sweet alert user_confirm
function ask_user_confirm(input)
{
    //example usage
    // var assign_traffic = [];
    // assign_traffic["url"] = "{{@BASE}}/emergency-traffic";
    // assign_traffic["target_id"] = "assign_emergency_traffic";
    // assign_traffic["reload_on_success"] = true;
    // assign_traffic["title"] = "تخصیص داده شود؟";
    // var assign_to_send = {};
    // assign_to_send["user_id"] = '{{ @user_id }}';
    // assign_traffic["to_send"] = assign_to_send;
    // ask_user_confirm(assign_traffic);
    input                           = input                        || [];
    input["title"]                  = input["title"]               || "title";
    input["html"]                   = input["html"]                || "";
    input["confirm_button_text"]    = input["confirm_button_text"] || "تایید";
    input["cancel_button_text"]     = input["cancel_button_text"]  || "انصراف";
    input["url"]                    = input["url"]                 || "";
    input["to_send"]                = input["to_send"]             || [];
    input["target_id"]              = input["target_id"]           || "";
    input["reload_on_success"]      = input["reload_on_success"]   || false;
    input["reload_target"]          = input["reload_target"]       || "";

    var target = '#' + input["target_id"];

    // $(target).click(function(){
        var to_send = {};
        to_send = input["to_send"];
        swal({
            type: 'question',
            title: input["title"],
            // text: 'text',
            html: input["html"] ,
            // text: input["html"],
            showLoaderOnConfirm: true,
            showCancelButton:true,
            confirmButtonText:input["confirm_button_text"],
            cancelButtonText: input["cancel_button_text"],
            cancelButtonColor:'#ff5d48',
            preConfirm: function() {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: input["url"],
                        data: to_send,
                        type: 'POST'
                    }).done(function(data) {
                        data = JSON.parse(data);
                        if(data["success"])
                            resolve(data['msg'] || "no message to show" );
                        else
                            reject(data['error']);
                    });
                });
            }
        }).then(function(data) {
            data = data || "";
            var msg = '<span style="font-family:IRANSans">';
            msg+= data;
            msg+="</span>";
            swal({
                type: 'success',
                title: 'Results',
                html: msg
            }).then(function(){
               if( input["reload_on_success"] )
               {
                 if(input["reload_target"] != "")
                 {
                    window.location = input["reload_target"];
                 }else
                 {
                    location.reload();
                 }
               }
               return false;
            });
        });

    // });
}


function valid_national_code(vmc){
    var mc;return mc=vmc,10==mc.length?"1111111111"==mc||"0000000000"==mc||"2222222222"==mc||"3333333333"==mc||"4444444444"==mc||"5555555555"==mc||"6666666666"==mc||"7777777777"==mc||"8888888888"==mc||"9999999999"==mc?!1:(c=parseInt(mc.charAt(9)),n=10*parseInt(mc.charAt(0))+9*parseInt(mc.charAt(1))+8*parseInt(mc.charAt(2))+7*parseInt(mc.charAt(3))+6*parseInt(mc.charAt(4))+5*parseInt(mc.charAt(5))+4*parseInt(mc.charAt(6))+3*parseInt(mc.charAt(7))+2*parseInt(mc.charAt(8)),r=n-11*parseInt(n/11),0==r&&r==c||1==r&&1==c||r>1&&c==11-r?!0:!1):!1
}

function setSelectionRange(input, selectionStart, selectionEnd) {
  if (input.setSelectionRange) {
    input.focus();
    input.setSelectionRange(selectionStart, selectionEnd);
  } else if (input.createTextRange) {
    var range = input.createTextRange();
    range.collapse(true);
    range.moveEnd('character', selectionEnd);
    range.moveStart('character', selectionStart);
    range.select();
  }
}
function setCaretToPos(input, pos) {
  setSelectionRange(input, pos, pos);
}

function anim_load(divv, htmll) {
    $(divv).animate({
        opacity: 1,
        height: 'toggle'
    }, 200, function() {
        // Animation complete. toggle
    }).html(htmll).promise().done(function() {
        $(divv).animate({
            opacity: 1,
            height: 'toggle'
        }, 400, function() {
            // Animation complete.
        });
    });
}

function LoadAjaxContent(url) {
    $('.preloader').show();
    $.ajax({
        mimeType: 'text/html; charset=utf-8', // ! Need set mimeType only when run from local file
        url: url,
        type: 'GET',
        success: function(data) {
            $('#ajax-content').html(data);
            $('.preloader').hide();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        dataType: "html",
        async: false
    });
}

function LoadAjaxContentInDiv(url, div) {
    $.ajax({
        mimeType: 'text/html; charset=utf-8',
        url: url,
        type: 'GET',
        success: function(data) {
            $('#' + div).html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        dataType: "html",
        async: false
    });
}

function ajax_post(ev, urll, form_id, container_id) {
    ev.preventDefault();

    $.ajax({
        type: "POST",
        url: urll,
        data: $(form_id).serialize(),
        dataType: "html",
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Ajax-Request", "true");
        },
        success: function(response) {
            anim_load(container_id, response);
        },
        async: false
    });
}

function ajax_post_with_data(ev, urll, dataa, container_id) {
    if (ev)
        ev.preventDefault();

    $.ajax({
        type: "POST",
        url: urll,
        data: dataa,
        dataType: "html",
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Ajax-Request", "true");
        },
        success: function(response) {
            anim_load(container_id, response);
        },
        async: false
    });
}

function anim_get_url_to_div(url, div) {
    $.ajax({
        mimeType: 'text/html; charset=utf-8',
        url: url,
        type: 'GET',
        success: function(data) {
            anim_load('#' + div,data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        },
        dataType: "html",
        async: false
    });
}


function show_loading(parent_elem)
{
    if (!parent_elem)
        parent_elem='body';
    var loading_div = '';
    loading_div += '<div id="loading_div">';
    loading_div += '<div style="';
    loading_div += 'position: fixed; ';
    loading_div += 'width: 100%; ';
    loading_div += 'height: 100%; ';
    loading_div += 'z-index: 99998; ';
    loading_div += 'background-color: #fff; ';
    loading_div += 'top: 0px; ';
    loading_div += 'left: 0px; ';
    loading_div += 'opacity: 0.4; ';
    loading_div += '">';
    loading_div += '</div>';
    loading_div += '<div style="position:fixed;top: 9px; left: 29px; z-index: 99999;">';
    loading_div += '<div class="cssload-container"><div class="cssload-speeding-wheel"></div></div>';
    loading_div += '</div>';
    loading_div += '</div>';
    $(parent_elem).append(loading_div);
}

function hide_loading(parent_elem)
{
    if (!parent_elem)
        parent_elem = 'body';

    $(parent_elem).find('#loading_div').remove();
}

function show_loading_box(parent_elem){
    if (!parent_elem)
        parent_elem = 'body';

    var container = $(parent_elem);
    var container_height = container.innerHeight();
    var container_width = container.innerWidth();
    // container.html('');
    var loading_div = '';
    loading_div += '<div style="margin-top:20px; margin-bottom:20px; text-align: center">';
    loading_div += '<div class="cssload-container"><div class="cssload-speeding-wheel"></div></div>';
    loading_div += '</div>';
    $(parent_elem).append(loading_div);
}

function hide_loading_box(parent_elem){
    if (!parent_elem)
        parent_elem = 'body';

    $(parent_elem).find('#loading_div').remove();
}

function box_load(container_id, ajax_url){
    show_loading_box('#'+container_id);
    $.ajax({
        url: ajax_url,
        type: 'GET',
    })
    .done(function(data) {
        hide_loading_box('#'+container_id);
        $('#'+container_id).html(data);
    });
}

function ReadCookie(var_name){
   var allcookies = document.cookie;

   // Get all the cookies pairs in an array
   cookiearray = allcookies.split(';');

   // Now take key value pair out of this array
   for(var i=0; i<cookiearray.length; i++){
      name = cookiearray[i].split('=')[0];
      value = cookiearray[i].split('=')[1];
      if (name == var_name)
        return value;
      //document.write ("Key is : " + name + " and Value is : " + value);
   }
}

function WriteCookie(var_name, var_value){
    document.cookie=var_name+'='+var_value;
}

function str_replace(search, replace, subject, countObj) {

      //      note 1: The countObj parameter (optional) if used must be passed in as a
      //      note 1: object. The count will then be written by reference into it's `value` property
      //   example 1: str_replace(' ', '.', 'Kevin van Zonneveld')
      //   returns 1: 'Kevin.van.Zonneveld'
      //   example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars')
      //   returns 2: 'hemmo, mars'
      //   example 3: str_replace(Array('S','F'),'x','ASDFASDF')
      //   returns 3: 'AxDxAxDx'
      //   example 4: var countObj = {}
      //   example 4: str_replace(['A','D'], ['x','y'] , 'ASDFASDF' , countObj)
      //   example 4: var $result = countObj.value
      //   returns 4: 4

      var i = 0;
      var j = 0;
      var temp = '';
      var repl = '';
      var sl = 0;
      var fl = 0;
      var f = [].concat(search);
      var r = [].concat(replace);
      var s = subject;
      var ra = Object.prototype.toString.call(r) === '[object Array]';
      var sa = Object.prototype.toString.call(s) === '[object Array]';
      s = [].concat(s);

      var $global = (typeof window !== 'undefined' ? window : GLOBAL);
      $global.$locutus = $global.$locutus || {};
      var $locutus = $global.$locutus;
      $locutus.php = $locutus.php || {};

      if (typeof (search) === 'object' && typeof (replace) === 'string') {
        temp = replace;
        replace = [];
        for (i = 0; i < search.length; i += 1) {
          replace[i] = temp;
        }
        temp = '';
        r = [].concat(replace);
        ra = Object.prototype.toString.call(r) === '[object Array]';
      }

      if (typeof countObj !== 'undefined') {
        countObj.value = 0;
      }

      for (i = 0, sl = s.length; i < sl; i++) {
        if (s[i] === '') {
          continue;
        }
        for (j = 0, fl = f.length; j < fl; j++) {
          temp = s[i] + '';
          repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
          s[i] = (temp).split(f[j]).join(repl);
          if (typeof countObj !== 'undefined') {
            countObj.value += ((temp.split(f[j])).length - 1);
          }
        }
      }
      return sa ? s : s[0];
}

function generate_random_string(length){
    var text = "";
    if (!length)
        var length = 8;
    //var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var possible = "2345678abcdefhkmprstxwyz";

    for( var i=0; i < length; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}



function persian_duration(duration_seconds){

    difference = duration_seconds * 1000;

    var daysDifference = Math.floor(difference / 1000 / 60 / 60 / 24);
    difference -= daysDifference * 1000 * 60 * 60 * 24;

    var hoursDifference = Math.floor(difference / 1000 / 60 / 60);
    difference -= hoursDifference * 1000 * 60 * 60;

    var minutesDifference = Math.floor(difference / 1000 / 60);
    difference -= minutesDifference * 1000 * 60;

    var secondsDifference = Math.floor(difference / 1000);

    //EN EXPORT
    //var out_str = 'difference = ' + daysDifference + ' day/s ' + hoursDifference + ' hour/s ' + minutesDifference + ' minute/s ' + secondsDifference + ' second/s ';

    //FA EXPORT
    var out_str = '';
    if (daysDifference != 0)
    {
        out_str += daysDifference + ' روز ';
    }
    if (hoursDifference != 0)
    {
        out_str += hoursDifference + ' ساعت ' ;
    }
    if (minutesDifference != 0)
    {
        out_str += minutesDifference + ' دقیقه ' ;
    }
    if ((hoursDifference == 0) && (daysDifference == 0))
    {
        if (secondsDifference != 0)
        {
            out_str += secondsDifference + ' ثانیه ' ;
        }
    }
    if (out_str == "")
    {
        out_str = "0";
    }
    return out_str;
}

function persian_traffic(in_bytes){
    var unit = "بایت";
    var traf = in_bytes;
    if (traf >= 1024)
    {
        unit = 'کیلوبایت';
        traf = Math.round((traf/1024)*100) / 100;

        if (traf >= 1024)
        {
            unit = 'مگابایت';
            traf = Math.round((traf/1024)*100) / 100;

            if (traf >= 1024)
            {
                unit = 'گیگابایت';
                traf = Math.round((traf/1024)*100) / 100;

                if (traf >= 1024)
                {
                    unit = 'ترابایت';
                    traf = Math.round((traf/1024)*100) / 100;
                }
            }
        }
    }
    return traf+' '+unit;
}

function number_format(nStr){
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function format_decimal(dec){
    return parseFloat(Math.round(dec * 100) / 100).toFixed(2);
}


function format_con_type(con_type){
    if (con_type == "Virtual")
        return "VPN";
    else if (con_type == "Ethernet")
        return "PPPOE";
    else if (con_type == "Wireless")
        return "HOTSPOT";
    else
        return "---";
}

function format_successful(ss){
    if ((ss == true) || (ss == 't'))
        return '<span style="color:green">موفق</span>';
    else
        return '<span style="color:red">ناموفق</span>';
}

function format_validity_days(days){
    return days + ' روز ';
}

function GetGregorianISO(input_date, input_date_type){
    if (!input_date) // may be its empty
    return "";

    try{
      if (input_date_type == "unix"){
          if (isInt(input_date)){ // its unix timestamp
              return moment.unix(input_date).format("YYYY-MM-DD HH:mm:ss");
          }
      }
      else{ // its ISO timestamp
          if (input_date_type == "jalali")
              return moment(input_date,"jYYYY-jMM-jDD HH:mm:ss").format("YYYY-MM-DD HH:mm:ss");
      }
    }
    catch(e){
          Data.raw_toast(input_date + ' Is Invalid Date');
          return false;
    }
}

function GregorianISO2JalaliISO(input_date){
    if (!input_date) // may be its empty
        return "";
    return moment(input_date,"YYYY-MM-DD HH:mm:ss").format("jYYYY-jMM-jDD HH:mm:ss");
}

function UnixToJalali(unix_date){
    if (unix_date != null)
        return moment(unix_date,"X").format("jYYYY-jMM-jDD HH:mm:SS");
    else
        return null
}

function JalaliToUnix(jalali_date){
    if (jalali_date != null)
        return moment(jalali_date,"jYYYY-jMM-jDD HH:mm:SS").format("X");
    else
        return null;
}


function UUP(url, param, paramVal){
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (i=0; i<tempArray.length; i++){
            if(tempArray[i].split('=')[0] != param){
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}



Url = {
    get get(){
        var vars= {};
        var cur_url = document.location.href;
        if(cur_url.length!==0)
            cur_url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value){
                key=decodeURIComponent(key);
                if(typeof vars[key]==="undefined") {vars[key]= decodeURIComponent(value);}
                else {vars[key]= [].concat(vars[key], decodeURIComponent(value));}
            });
        return vars;
    }
};

function get_url_vars(){
    return Url.get;
}

function jqval(elem_id){

    return $('#'+elem_id).val();
}
// converts persian digits to english equivalents
function toEnDigits(str) {
    str = str && str.toString().replace(/[\u06F0\u06F1\u06F2\u06F3\u06F4\u06F5\u06F6\u06F7\u06F8\u06F9]/g, function(v){return String.fromCharCode(v.charCodeAt(0) - 1728)}).replace(/[\u0660\u0661\u0662\u0663\u0664\u0665\u0666\u0667\u0668\u0669]/g, function(v){return String.fromCharCode(v.charCodeAt(0) - 1584)}).replace(/[\u066B]/g, '.');
    return str;
}

var cookieWrapper = (function() {
    "use strict";
    // associative array indexed as cookies["name"] = "value"
    var cookies = new Object(),

        extractCookies = function () {
            var name, value;
            var beginning, middle, end;
            for (name in cookies) { // if there are any entries currently, get rid of them
                cookies = new Object();
                break;
            }
            beginning = 0;  // start at beginning of cookie string
            while (beginning < document.cookie.length) {
                middle = document.cookie.indexOf('=', beginning);  // find next =
                end = document.cookie.indexOf(';', beginning);  // find next ;
                if (end == -1)  // if no semicolon exists, it's the last cookie
                    end = document.cookie.length;
                if ((middle > end) || (middle == -1)) { // if the cookie has no value...
                    name = document.cookie.substring(beginning, end);
                    value = "";
                }
                else { // extract its value
                    name = document.cookie.substring(beginning, middle);
                    value = document.cookie.substring(middle + 1, end);
                }
                cookies[name] = unescape(value);  // add it to the associative array
                beginning = end + 2;  // step over space to beginning of next cookie
            }
            return cookies;
        },
        deleteCookie = function(name)
        {
            document.cookie = name + "=deleted; expires=Thu, 01-Jan-1970 00:00:01 GMT";
            document.cookie = name + "; expires=Thu, 01-Jan-1970 00:00:01 GMT";
        },
        cookiesEnabled  = function () {
            document.cookie = "cookiesenabled=yes";
            cookies = extractCookies();
            if(cookies["cookiesenabled"] == "yes"){
                deleteCookie("cookiesenabled");
                return true;
            }
            return false;
        };

    return { "extractCookies" : extractCookies,
        "deleteCookie" : deleteCookie,
        "cookiesEnabled" : cookiesEnabled};
}());



//-----------------------------------------------------------------
// taken from
//http://www.codeforge.com/read/248242/calendar.js__html
// better alternative would be
// https://github.com/leeper/astrojs
//-----------------------------------------------------------------
function mod(a, b) {

    return a - (b * Math.floor(a / b));

}

function jwday(j) {

    return mod(Math.floor((j + 1.5)), 7);

}

var Weekdays = new Array("Sunday", "Monday", "Tuesday", "Wednesday",
                          "Thursday", "Friday", "Saturday");

//  LEAP_GREGORIAN  --  Is a given year in the Gregorian calendar a leap year ?

function leap_gregorian(year) {

    return ((year % 4) == 0) &&
            (!(((year % 100) == 0) && ((year % 400) != 0)));

}

//  GREGORIAN_TO_JD  --  Determine Julian day number from Gregorian calendar date

var GREGORIAN_EPOCH = 1721425.5;

function gregorian_to_jd(year, month, day) {

    return (GREGORIAN_EPOCH - 1) +
           (365 * (year - 1)) +
           Math.floor((year - 1) / 4) +
           (-Math.floor((year - 1) / 100)) +
           Math.floor((year - 1) / 400) +
           Math.floor((((367 * month) - 362) / 12) +
           ((month <= 2) ? 0 :
                               (leap_gregorian(year) ? -1 : -2)
           ) +
           day);

}

//  JD_TO_GREGORIAN  --  Calculate Gregorian calendar date from Julian day

function jd_to_gregorian(jd) {

    var wjd, depoch, quadricent, dqc, cent, dcent, quad, dquad,
        yindex, year, yearday, leapadj;

    wjd = Math.floor(jd - 0.5) + 0.5;
    depoch = wjd - GREGORIAN_EPOCH;
    quadricent = Math.floor(depoch / 146097);
    dqc = mod(depoch, 146097);
    cent = Math.floor(dqc / 36524);
    dcent = mod(dqc, 36524);
    quad = Math.floor(dcent / 1461);
    dquad = mod(dcent, 1461);
    yindex = Math.floor(dquad / 365);
    year = (quadricent * 400) + (cent * 100) + (quad * 4) + yindex;
    if (!((cent == 4) || (yindex == 4))) {

        year++;
    
}
    yearday = wjd - gregorian_to_jd(year, 1, 1);
    leapadj = ((wjd < gregorian_to_jd(year, 3, 1)) ? 0
                                                  :
                  (leap_gregorian(year) ? 1 : 2)
              );
    var month = Math.floor((((yearday + leapadj) * 12) + 373) / 367);
    var day = (wjd - gregorian_to_jd(year, month, 1)) + 1;

    return new Array(year, month, day);

}

//  LEAP_PERSIAN  --  Is a given year a leap year in the Persian calendar ?

function leap_persian(year) {

    return ((((((year - ((year > 0) ? 474 : 473)) % 2820) + 474) + 38) * 682) % 2816) < 682;

}

//  PERSIAN_TO_JD  --  Determine Julian day from Persian date

var PERSIAN_EPOCH = 1948320.5;
var PERSIAN_WEEKDAYS = new Array("دوشنبه", "یکشنبه",
                                 "چهارشنبه", "سه شنبه",
                                 "شنبه", "جمعه", "پنج شنبه");

function persian_to_jd(year, month, day) {

    var epbase, epyear;
    epbase = year - ((year >= 0) ? 474 : 473);
    epyear = 474 + mod(epbase, 2820);
    return day +
            ((month <= 7) ?
                ((month - 1) * 31) :
                (((month - 1) * 30) + 6)
            ) +
            Math.floor(((epyear * 682) - 110) / 2816) +
            (epyear - 1) * 365 +
            Math.floor(epbase / 2820) * 1029983 +
            (PERSIAN_EPOCH - 1);

}

//  JD_TO_PERSIAN  --  Calculate Persian date from Julian day

function jd_to_persian(jd) {

    var year, month, day, depoch, cycle, cyear, ycycle,
        aux1, aux2, yday;
    jd = Math.floor(jd) + 0.5;
    depoch = jd - persian_to_jd(475, 1, 1);
    cycle = Math.floor(depoch / 1029983);
    cyear = mod(depoch, 1029983);
    if (cyear == 1029982) {

        ycycle = 2820;
    
} else {

        aux1 = Math.floor(cyear / 366);
        aux2 = mod(cyear, 366);
        ycycle = Math.floor(((2134 * aux1) + (2816 * aux2) + 2815) / 1028522) + aux1 + 1;
    
}
    year = ycycle + (2820 * cycle) + 474;
    if (year <= 0) {

        year--;
    
}
    yday = (jd - persian_to_jd(year, 1, 1)) + 1;
    month = (yday <= 186) ? Math.ceil(yday / 31) : Math.ceil((yday - 6) / 30);
    day = (jd - persian_to_jd(year, month, 1)) + 1;
    return new Array(year, month, day);

}

//تاریخ میلادی برمیگرداند
function calcPersian(year, month, day) {

    var date, j;
    j = persian_to_jd(year, month, day);
    date = jd_to_gregorian(j);
    var weekday = jwday(j);
    return new Array(date[0], date[1], date[2], weekday);

}

//  calcGregorian  --  Perform calculation starting with a Gregorian date
//تاریخ شمسی را بر می گرداند
function calcGregorian(year, month, day) {

    month--;
    var j, weekday;
    //  Update Julian day
    j = gregorian_to_jd(year, month + 1, day) +
           (Math.floor(0 + 60 * (0 + 60 * 0) + 0.5) / 86400.0);
    //  Update Persian Calendar
    var perscal = jd_to_persian(j);
    weekday = jwday(j);
    return new Array(perscal[0], perscal[1], perscal[2], weekday);

}

function getTodayGregorian() {

    var t = new Date();
    var today = new Date();
    var y = today.getYear();
    if (y < 1000) {

        y += 1900;
    
}
    return new Array(y, today.getMonth() + 1, today.getDate(), t.getDay());

}

function GetTodayCalendarInPersian() {

    var t = new Date();
    var today = getTodayGregorian();
    var persian = calcGregorian(today[0], today[1], today[2]);
    return new Array(persian[0], persian[1], persian[2], t.getDay());

}       
//-------------------------------------------------------------------------
function Input(name){
  this._name = name;
  this._id = name;
  this._classes = [];
  this._styles = [];
 }
 Input.prototype.setType=function(type){
  this._type = type;
  return this;
 }
 Input.prototype.setId = function(id){
  this._id = id;
  return this;
 }
 Input.prototype.setName = function(name){
  this._name = name;
  return this;
 }
 Input.prototype.addClass = function(klass){
  this._classes.push(klass);
  return this;
 }

 Input.prototype.setPlaceholder = function(place_holder){
  this._placeholder=place_holder;
  return this;
 }

 Input.prototype.setValue = function(value){
  this._value=value;
  return this;
 }

 Input.prototype.addStyle = function(style){
  this._styles.push(style);
  return this;
 }
 
 Input.prototype.toString = function(){
  var ret = "<input type='text' ";
  if(this._id)
   ret += "id='" + this._id +"' ";
  if(this._name)
   ret += "name='" + this._name + "' ";
  if(this._classes)
   ret += "class='" + this._classes.join(" ") + "' ";
  if(this._placeholder)
   ret += "placeholder='" + this._placeholder + "' ";
  if(this._styles)
   ret += "style='" + this._styles.join(";") + "' ";
  if(this._value)
   ret += "value='" + this._value + "' ";
  return ret + ">";
 }

 function form_group(c,id){
    id = id ? id : '';
    ret='<div class="form-body col-xs-6" id="'+id+'"> \
          <div class="form-group">';
    ret += c;
    ret +='\
          </div> \
      </div>';
    return ret;
 }

//-------------------------------------------------------------------------
function isjson(txt){
  try {
     var json = JSON.parse(txt);
  } catch(e) {
     return false;
  }
  return true;
}

function SetVisible(m) {
    document.getElementById(m).style.visibility='';
}

function SetHidden(m) {
    document.getElementById(m).style.visibility='hidden';
}
//------------------------------------------------------------------------------

String.prototype.toFaDigit = function() {
    return this.replace(/\d+/g, function(digit) {
        var ret = '';
        for (var i = 0, len = digit.length; i < len; i++) {
            ret += String.fromCharCode(digit.charCodeAt(i) + 1728);
        }
        return ret;
    });
};
String.prototype.toEnDigit = function() {
    return this.replace(/[\u06F0-\u06F9]+/g, function(digit) {
        var ret = '';
        for (var i = 0, len = digit.length; i < len; i++) {
            ret += String.fromCharCode(digit.charCodeAt(i) - 1728);
        }
        return ret;
    });
};

function hazfe_fasele(id){
    var mytext = $('#'+id).val().trim();
    var mytext = mytext.replace(/ا /g, 'ا');
    var mytext = mytext.replace(/د /g, 'د');
    var mytext = mytext.replace(/ذ /g, 'ذ');
    var mytext = mytext.replace(/و /g, 'و');
    var mytext = mytext.replace(/ر /g, 'ر');
    var mytext = mytext.replace(/ز /g, 'ز');
    var mytext = mytext.replace(/ژ /g, 'ژ');
    var mytext = mytext.replace(/0 /g, '0');
    var mytext = mytext.replace(/1 /g, '1');
    var mytext = mytext.replace(/2 /g, '2');
    var mytext = mytext.replace(/3 /g, '3');
    var mytext = mytext.replace(/4 /g, '4');
    var mytext = mytext.replace(/5 /g, '5');
    var mytext = mytext.replace(/6 /g, '6');
    var mytext = mytext.replace(/7 /g, '7');
    var mytext = mytext.replace(/8 /g, '8');
    var mytext = mytext.replace(/9 /g, '9');
    var mytext = mytext.replace(/  /g, ' ');
    var mytext = mytext.replace(/۰ /g, '۰');
    var mytext = mytext.replace(/۱ /g, '۱');
    var mytext = mytext.replace(/۲ /g, '۲');
    var mytext = mytext.replace(/۳ /g, '۳');
    var mytext = mytext.replace(/۴ /g, '۴');
    var mytext = mytext.replace(/۵ /g, '۵');
    var mytext = mytext.replace(/۶ /g, '۶');
    var mytext = mytext.replace(/۷ /g, '۷');
    var mytext = mytext.replace(/۸ /g, '۸');
    var mytext = mytext.replace(/۹ /g, '۹');
    $('#'+id).val(mytext);
}
//----------------------------------------------------------------------------------------------------------------------
function compile_mustache(view_name, view) {
    var template = document.getElementById(view_name).innerHTML;
    var output = Mustache.render(template, view);
    return output;
}

function compile_handlebar(view_name,data,target){
    var template = Handlebars.compile(document.getElementById(view_name).innerHTML);
    var ret = template(data);
    if(target){
        $(target).innerHTML = ret
    }
    return ret;
}


function guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
}

function add_index(arr){
    for(var i = 0; i < arr.length; ++i){
        arr[i]["index"] = i+1;
    }
    return arr;
}

function add_index(arr){
    for(var i = 0; i < arr.length; ++i){
        arr[i]["index"] = i+1;
    }
    return arr;
}
///-------------------------------- random test values -----------------------------------------------------------------

function rand_choice(arr){
    return arr[random.rand(0,arr.length-1)];
}

function random_name(){
    var names = ["علی","رضا","محمد","حسن","اردشیر","اردلان","قاسم","سیروس","مهران","مریم","زهرا","فاطمه","کریم"];
    return names[random.rand(0,names.length-1)];
}

function random_family(){
    var families = ["منصوری","ایمانی","خلیلی","هاشمی","ادیب","دهقانی","غفوری","مدیری","کیانیان","رهبر","سردار","فیروز"];
    return families[random.rand(0,families.length-1)];
}

function random_mobile(){
    var mobile = ["09359012233","09359012244","093590122","09179012233"];
    return mobile[random.rand(0,mobile.length-1)];
}

function random_tel(){
    var tel = ["8206778","8769089","23340565"];
    return tel[random.rand(0,tel.length-1)];
}

function random_city_zone(){
    var zones = ["عادل آباد","معالی آباد","تاچارا","اطلسی","ارتش","پایگاه"];
    return rand_choice(zones);
}

function random_alley(){
    a = ["کوچه 1","کوچه 2","کوچه 3","کوچه 4","کوچه 5","کوچه 6","کوچه اول","کوچه 5/1"];
    return rand_choice(a);
}

function random_street(){
    s = ["داریوش","زند","سراچ","ولیعسر","سپاسی","معدل","اردیبهشت","پیروزی","ملاصدرا","هدایت"];
    return rand_choice(s);
}

function random_persian_date(){
    //fixme year does not work with rand function
    var year = "1397";
    var month = random.rand(1,12);
    var day = random.rand(1,30);
    if(month>=1 && month <= 9){
        month = "0" + month;
    }
    if(day >=1 && day <=9){
        day = "0" + day;
    }
    return "" +year + "/" + month + "/" + day;
}

function add_index(arr){
    for(var i = 0; i < arr.length; ++i){
        arr[i]["index"] = i+1;
    }
    return arr;
}

function random_mcode(){
    function ab_mc(vmc){var mc;return mc=vmc,10==mc.length?"1111111111"==mc||"0000000000"==mc||"2222222222"==mc||"3333333333"==mc||"4444444444"==mc||"5555555555"==mc||"6666666666"==mc||"7777777777"==mc||"8888888888"==mc||"9999999999"==mc?!1:(c=parseInt(mc.charAt(9)),n=10*parseInt(mc.charAt(0))+9*parseInt(mc.charAt(1))+8*parseInt(mc.charAt(2))+7*parseInt(mc.charAt(3))+6*parseInt(mc.charAt(4))+5*parseInt(mc.charAt(5))+4*parseInt(mc.charAt(6))+3*parseInt(mc.charAt(7))+2*parseInt(mc.charAt(8)),r=n-11*parseInt(n/11),0==r&&r==c||1==r&&1==c||r>1&&c==11-r?!0:!1):!1}
    var count = 0;
    var num = 0;
    do{
        num = random.rand(1111111111, 9999999999);
        count++;
        if(count>1000) break;
    }while(! ab_mc(""+num) );
    return num;
}


function sval(selector, val) {
    $(selector).val(val);
}

function val(selector) {
    return $(selector).val();
}


function http_build_query(params) {
    return Object.keys(params).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(params[k]);
    }).join("&");
}

function http_build_url(url, params) {
    return url + "?" + http_build_query(params);
}

if (!String.prototype.format) {
    String.prototype.format = function () {
        var args = arguments;
        return this.replace(/{(\d+)}/g, function (match, number) {
            return typeof args[number] != 'undefined'
                ? args[number]
                : match
                ;
        });
    };
}

function assert($condition,$msg){
    console.assert($condition,$msg);
}

function isASCII(str) {
    return /^[\x00-\x7F]*$/.test(str);
}


function ask_user_confirm(input)
{
    //example usage
    // var assign_traffic = [];
    // assign_traffic["url"] = "{{@BASE}}/emergency-traffic";
    // assign_traffic["target_id"] = "assign_emergency_traffic";
    // assign_traffic["reload_on_success"] = true;
    // assign_traffic["title"] = "ØªØ®ØµÛŒØµ Ø¯Ø§Ø¯Ù‡ Ø´ÙˆØ¯ØŸ";
    // var assign_to_send = {};
    // assign_to_send["user_id"] = '{{ @user_id }}';
    // assign_traffic["to_send"] = assign_to_send;
    // ask_user_confirm(assign_traffic);
    // example usage 2
     // var dlg = {
     //                        "url": /update-admin-attrs",
     //                        "reload_on_success" : true,
     //                        "title" : "Update attributes",
     //                        "html": "Are you sure",
     //                        "to_send":  to_send
     //                    };
    input                           = input                        || [];
    input["title"]                  = input["title"]               || "title";
    input["confirm_button_text"]    = input["confirm_button_text"] || "ØªØ§ÛŒÛŒØ¯";
    input["cancel_button_text"]     = input["cancel_button_text"]  || "Ø§Ù†ØµØ±Ø§Ù�";
    input["url"]                    = input["url"]                 || "";
    input["to_send"]                = input["to_send"]             || [];
    input["target_id"]              = input["target_id"]           || "";
    input["reload_on_success"]      = input["reload_on_success"]   || false;

    var target = '#' + input["target_id"];

    // $(target).click(function(){
        var to_send = {};
        to_send = input["to_send"];
        swal({
            type: 'question',
            title: input["title"],
            html: "",
            showLoaderOnConfirm: true,
            showCancelButton:true,
            confirmButtonText:input["confirm_button_text"],
            cancelButtonText: input["cancel_button_text"],
            cancelButtonColor:'#ff5d48',
            preConfirm: function() {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: input["url"],
                        data: to_send,
                        type: 'POST'
                    }).done(function(data) {
                        data = JSON.parse(data);
                        if(data["success"])
                            resolve(data['msg'] || "no message to show" );
                        else
                            reject(data['error']);
                    });
                });
            }
        }).then(function(data) {
            data = data || "";
            var msg = '<span style="font-family:IRANSans">';
            msg+= data;
            msg+="</span>";
            swal({
                type: 'success',
                title: '',
                html: msg
            }).then(function(){
               return false;
               if( input["reload_on_success"] )
               {
                 location.reload();
               }
            });
        });

    // });

}

function show_warning_msg(msg)
{
    swal(msg, "", "warning");
}

function show_info_msg(msg,title)
{
    title = title || '';
    msg = msg || '';
    swal({"title":title,
        type:"info",
        text:msg});
}

function show_success_msg(msg)
{
    swal(msg,"", "success");
}

function show_message(msg)
{
    swal(msg);
}


function make_yes_no_swal(opt) {
    opt          = opt || {};
    opt['title'] = opt['title'] ? opt['title'] : '';
    opt['text']  = opt['text'] ? opt['text'] : '';

    return swal({
        title: opt['title'],
        text: opt['text'],
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'بلی',
        cancelButtonText: 'خیر',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true
    })
}

function is_valid_mobile_number(mobile_number){
  return /09[0-9]{9}/.test(mobile_number);
}

function is_valid_national_code(vmc){
    var mc;return mc=vmc,10==mc.length?"1111111111"==mc||"0000000000"==mc||"2222222222"==mc||"3333333333"==mc||"4444444444"==mc||"5555555555"==mc||"6666666666"==mc||"7777777777"==mc||"8888888888"==mc||"9999999999"==mc?!1:(c=parseInt(mc.charAt(9)),n=10*parseInt(mc.charAt(0))+9*parseInt(mc.charAt(1))+8*parseInt(mc.charAt(2))+7*parseInt(mc.charAt(3))+6*parseInt(mc.charAt(4))+5*parseInt(mc.charAt(5))+4*parseInt(mc.charAt(6))+3*parseInt(mc.charAt(7))+2*parseInt(mc.charAt(8)),r=n-11*parseInt(n/11),0==r&&r==c||1==r&&1==c||r>1&&c==11-r?!0:!1):!1;
}
// Parsleyjs validations
if(window.Parsley){

    // validates mobile number
     window.Parsley.addValidator('validMobile', {
        validateString: function (value) {
            return is_valid_mobile_number(value);
        },
        messages: {
            en: "شماره موبایل نامعتبر"
        }
    });

     // validates national code
         window.Parsley.addValidator('validNationalCode',{
        validateString:function(value){
            return valid_national_code(value);
        },
        messages:{
            en: "کد ملی نامعتبر"
        }
    });

}