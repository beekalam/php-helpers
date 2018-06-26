<?php

$context = new ZMQContext();

//  Socket to receive messages on
$receiver = new ZMQSocket($context, ZMQ::SOCKET_PULL);
$receiver->connect("tcp://localhost:5557");

//  Socket to send messages to
$sender = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
$sender->connect("tcp://localhost:5558");

//  Process tasks forever
while (true) {
    $string = $receiver->recv();

    //  Simple progress indicator for the viewer
    echo $string, PHP_EOL;

    //  Do the work
    usleep($string * 1000);

    //  Send results to sink
    $sender->send("");
}