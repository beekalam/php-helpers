<?php
class fluent{
    private $input;
    private $res;
    public function __construct($input){
        $this->input = $input;
    }
    public function __call($name,$arguments){
        if($name == 'res'){
            return $this->res;
        }else if($name=='toObjekt'){
            return new Objekt($this->res);
        }
        if(count($arguments) == 0)
            $this->res = $this->input->{$name}();
        else if(count($arguments)==1)
            $this->res = $this->input->{$name}($arguments[0]);
        else if(count($arguments) == 2)
            $this->res = $this->input->{$name}($arguments[0],$arguments[1]);
        else if(count($arguments) == 3)
            $this->res = $this->input->{$name}($arguments[0],$arguments[1],$arguments[2]);
        else if(count($arguments) == 4)
            $this->res = $this->input->{$name}($arguments[0],$arguments[1],$arguments[2],$arguments[3]);
        else if(count($arguments) == 5)
            $this->res = $this->input->{$name}($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4]);
        return $this;
    }
}

function flu($input){
    return new fluent($input);
}