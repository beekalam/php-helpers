<?php

function expose_sessions()
{
    $path   = ini_get('session.save_path');
    $path   = realpath($path);
    $handle = dir($path);
    while ($filename = $handle->read()) {

        if (substr($filename, 0, 10) == 'ci_session') {
            $data = file_get_contents("$path/$filename");

            if (!empty($data)) {
                echo nl2br("Session [" . substr($filename, 5) . "]\n");
                echo nl2br(print_r( Session::unserialize($data)));
                echo nl2br("\n--\n\n");
            }
        }
    }

}