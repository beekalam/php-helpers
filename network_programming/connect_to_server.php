<?php
if (!($sock = socket_create(AF_INET, SOCK_STREAM, 0))) {
    $errorcode = socket_last_error();
    $errormsg  = socket_strerror($errorcode);

    die("Couldn't create socket: [$errorcode] $errormsg \n");
}

echo "Socket created \n";

if (!socket_connect($sock, '74.125.235.20', 80)) {
    $errorcode = socket_last_error();
    $errormsg  = socket_strerror($errorcode);

    die("Could not connect: [$errorcode] $errormsg \n");
}

echo "Connection established \n";