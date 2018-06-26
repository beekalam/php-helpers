function isprime(m){
	if(m == 0 || m == 1) return false;
	if(m%2 == 0) return false;
	for(var i = 3,s = Math.sqrt(m); i <= s ;i++){
	    if(m%i == 0) return false;
	}
	return true;
}