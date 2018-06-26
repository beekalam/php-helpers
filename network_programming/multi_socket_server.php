<?php
set_time_limit(0);

$NULL = NULL;

$address = "127.0.0.1";
$port    = 4545;

$max_clients    = 10;
$client_sockets = array();

$master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

$res = true;

$res &= @socket_bind($master, $address, $port);
$res &= @socket_listen($master);

if (!$res) {

    die("Could not bind and listen on $address:$port\n");

}

$abort = false;

$read = array($master);

while (!$abort) {

    $num_changed = socket_select($read, $NULL, $NULL, 0, 10);

    /* Did any change? */
    if ($num_changed) {

        /* Did the master change (new connection) */

        if (in_array($master, $read)) {

            if (count($client_sockets) < $max_clients) {

                $client_sockets[] = socket_accept($master);
                echo "Accepting connection (" . count($client_sockets) .
                    " of $max_clients)\n";

            }
        }

        /* Cycle through each client to see if any of them changed */
        foreach ($client_sockets as $key => $client) {

            /* New data on a client socket? Read it and respond */
            if (in_array($client, $read)) {
                $input = socket_read($client, 1024);

                if ($input === false) {

                    socket_shutdown($client);
                    unset($client_sockets[$key]);

                } else {

                    $input = trim($input);

                    if (!@socket_write($client, "You said: $input\n")) {
                        socket_close($client);
                        unset($client_sockets[$key]);
                    }

                }

                if ($input == 'exit') {

                    socket_shutdown($master);
                    $abort = true;
                }

            }

        }

    }

    $read   = $client_sockets;
    $read[] = $master;

}
