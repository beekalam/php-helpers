<?php
// determining the host name of a remote ip
function eg_gethostname_byaddr($ip)
{
    $hostname = gethostbyaddr($ip);
    return $hostname;
}

function eg_gethostbyname($hostname)
{
    $ip_addr = gethostbyname($hostname);
    return $ip_addr;
}

function eg_get_all_ips_associated_with_domain($domain)
{
    $ip_addresses = gethostbynamel($domain);
    return $ip_addresses;
}

function eg_dns_check_record($hostname, $type)
{
    if (dns_check_record($hostname, $type)) {
        return true;
    }
    return false;
}

function eg_dns_get_record($hostname, $type = DNS_ALL)
{
    $records = dns_get_record($hostname, $type);
    return $records;
}

function eg_getprotbyname($proto = "snmp")
{
    $proto_num = getprotobyname($proto);
    if ($proto_num == -1) {
        die("Could not find protocol '$proto'\n");
    } else {
        echo "The '$proto' protocol has an ID of $proto_num\n";
    }

}

function eg_getprotobynumber($proto_num = SOL_TCP)
{
    $proto = getprotobynumber($proto_num);
    if ($proto === false) {
        die("Could not find protocol with ID $proto_num\n");
    } else {
        echo "The protocol with ID $proto_num is '$proto'\n";
    }
}

function eg_getservbyname($service = "ftp")
{
    $port = getservbyname($service, 'tcp');
    if ($port === false) {
        die("Lookup of service '$service' failed.\n");
    } else {
        echo "Service '$service' runs on port $port\n";
    }
}

function eg_getservbyport($port = 80)
{
    $service = getservbyport($port, 'tcp');
    if ($service === false) {
        die("Could not find a service running on $port\n");
    } else {
        echo "The '$service' service runs on port $port\n";
    }

}

//---------------------sockets -------------------------------
function eg_retrieve_website_using_sockets($address, $port = 80)
{
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_connect($socket, $address, $port);
    socket_write($socket, "GET /index.php HTTP/1.0\n\n");
    $result = "";
    while ($read = socket_read($socket, 1024)) {
        $result .= $read;
    }
    socket_close($socket);
    return $result;
}


//dump("------------------------------------");
//echo eg_gethostname_byaddr("94.74.128.169");
//dump(eg_gethostbyname("www.google.com"));
//dump(eg_get_all_ips_associated_with_domain("fanacmp.ir"));
//dump(eg_dns_check_record("fanacmp.ir","NS"));
//dump(eg_dns_get_record("fanacmp.ir"));
//dump(eg_dns_get_record("google.com"));
//dump(eg_retrieve_website_using_sockets("127.0.0.1",80));
//dump(eg_retrieve_website_using_sockets("localhost", 80));
//dump("------------------------------------");