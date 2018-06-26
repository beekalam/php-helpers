is_lower = (c) => c >='a' && c <='z';
is_upper = (c) => c >='A' && c <='Z';
is_number = (c) => c == '1' || c=='2' || c == '3' || c=='4' || c=='5' || c=='6' || c=='7' || c=='8' || c=='9' || c=='0';
lower_count = (s) => s.split('').filter((c) => is_lower(c)).length;
upper_count = (s) => s.length - lower_count(s);
to_fixed_float = (a,num) => parseFloat(a.toFixed(num));
reverse = (word) => word.split('').reverse().join('');
