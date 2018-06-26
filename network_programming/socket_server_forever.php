<?php
error_reporting(E_ALL);

/* Allow the script to hang around waiting for connections. */
set_time_limit(0);

/* Turn on implicit output flushing so we see what we're getting
 * as it comes in. */
ob_implicit_flush();

$address = '0.0.0.0';
$port    = 5100;
$sock    = null;
function init_socket(&$sock, $address, $port)
{
    if (!isset($address)) die("address is not set.");
    if (!isset($port)) die("port is not set.");
    if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
        echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    }

    if (socket_bind($sock, $address, $port) === false) {
        echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
    }

    if (socket_listen($sock, 5) === false) {
        echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
    }
}

function writeln($line)
{
    echo date("H:i:s") . " " . $line . PHP_EOL;
}


do {
    writeln("initializing socket.");
    init_socket($sock, $address, $port);
    do {
        if (($msgsock = socket_accept($sock)) === false) {
            echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
            break;
        }
        /* Send instructions. */
        $msg = "\nWelcome to the PHP Test Server. \n" .
            "To quit, type 'quit'. To shut down the server type 'shutdown'.\n";
        socket_write($msgsock, $msg, strlen($msg));

        do {
            if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
                echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
                break 2;
            }
            if (!$buf = trim($buf)) {
                continue;
            }
            if ($buf == 'quit') {
                writeln("Quitting current connection.");
                break;
            }
            if ($buf == 'shutdown') {
                writeln("Shutting down server.");
                socket_close($msgsock);
                break 3;
            }
            if ($buf == 'time') {
                writeln("Received time command.");
                $talkback = time();
            }
            if ($buf == 'date') {
                writeln("Received date command.");
                $talkback = date('Y-m-d');
            }
            if ($buf == 'date-time') {
                writeln("Received date-time command.");
                sleep(4);
                $talkback = date('Y-m-d H:i:s');
            }
            $talkback .= "\n";
            socket_write($msgsock, $talkback, strlen($talkback));
        } while (true);
        socket_close($msgsock);
    } while (true);

    writeln("closing socket.");
    socket_close($sock);
} while (true);
?>