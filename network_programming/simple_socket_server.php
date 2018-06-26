<?php
$address = "127.0.0.1";
$port    = 4545;

set_time_limit(0);
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//creating a socket server is a thre step process
//the first step is to bind the socket to a particular address
socket_bind($socket, $address, $port);
//after being bound to a address,the socket must be instructed to listen for traffic attempting to
//communicate with it
socket_listen($socket);
//the third and final step in creating  a socket server is to actually instruct the socket
// itself to accept any incoming connections it receives.
$connection = socket_accept($socket);

$result = trim(socket_read($connection, 1024));

echo "Results received: '$result'\n";

socket_close($socket);

socket_shutdown($socket);
socket_close($socket);
