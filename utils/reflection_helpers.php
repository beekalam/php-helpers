<?php

if(!function_exists("class_properties"))
{
    function class_properties($class_name)
    {
        $reflector = new ReflectionClass($class_name);
        $properties = $reflector->getProperties();
        $ret = array();
        foreach ($properties as $p) {
            $ret[] = $p->name;
        }
        return $ret;
    }
}