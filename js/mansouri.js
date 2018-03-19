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