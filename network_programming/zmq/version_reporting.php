<?php
if (class_exists("ZMQ") && defined("ZMQ::LIBZMQ_VER")) {
    echo ZMQ::LIBZMQ_VER, PHP_EOL;
}