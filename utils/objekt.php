<?php

class Objekt
{
    private $value = null;

    public function __construct($input)
    {
        $this->value = $input;
    }

    public function dump($dump_func_name = "var_dump")
    {
        call_user_func($dump_func_name, $this->value);
        return $this;
    }

    public function die($message = "")
    {
        echo $message;
        exit;
    }

    public function then($obj){
        return $obj;
    }

}

function objekt($value)
{
    return new objekt($value);
}