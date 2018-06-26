<?php

class array_builder{
    private $arr;
    public function __construct($arr){
        if(is_object($arr))
            $this->arr = $this->object_to_array($arr);
        else
            $this->arr = $arr;
        return $this;
    }

    private function object_to_array($obj)
    {
        if (is_object($obj)) $obj = (array)$obj;
        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                $new[$key] = object_to_array($val);
            }
        } else $new = $obj;
        return $new;
    }

    public function merge($arr){
        $this->arr = array_merge($this->arr,$arr);
        return $this;
    }

    public function count(){
        return count($this->arr);
    }

    public function column($col_name){
        $this->arr = array_column($this->arr,$col_name);
        return $this;
    }

    public function except($col_name){
        foreach($this->arr as &$arr){
            if(array_key_exists($col_name,$arr)){
                unset($arr[$col_name]);
            }
        }
        return $this;
    }
    public function avg(){
        return array_sum($this->arr)/count($this->arr);
    }

    public function get(){
        return $this->arr;
    }

    public function keys(){
        return array_keys($this->arr);
    }

    public function values(){
        return builder(array_values($this->arr));
    }

    public function reverse(){
        return array_reverse($this->arr);
    }

    public function key_exists($key){
        return array_key_exists($key,$this->arr);
    }

    public function size(){
        return count($this->arr);
    }

    public function map($func){
        $new_arr = array();
        foreach($this->arr as $k=>$v){
            $new_arr[] = $func($k,$v);
        }
        $this->arr = $new_arr;
        return $this;
    }

    public function filter($func){
        $new_arr = array();
        foreach($this->arr as $k=>$v){
            if($func($k,$v)) $new_arr[]=array($k=>$v);
        }
        $this->arr = $new_arr;
        return $this;
    }

    public function toObjekt()
    {
        return new Objekt($this->get());
    }
}

function builder($input){
    return new array_builder($input);
}
