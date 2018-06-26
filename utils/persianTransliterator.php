<?php

class transliterator{
    private $input;
    private $map=array(
        'b'=>'ب',
        'c'=>'ک',
        'd'=>'د',
        'f'=>'ف',
        'g'=>'گ',
        'h'=>'ه',
        'j'=>'ج',
        'k'=>'ک',
        'l'=>'ل',
        'm'=>'م',
        'n'=>'ن',
        'p'=>'پ',
        'q'=>'ک',
        'r'=>'ر',
        's'=>'س',
        't'=>'ت',
        'v'=>'و',
        'w'=>'و',
        'x'=>'اکس',
        'y'=>'ی',
        'z'=>'ز');

    public function __construct($input=null){
        if(!is_null($input))
            $this->init($input);
        return $this;
    }

    public function init($input){
        $this->input = $input;
    }

    public function transliterate($input=null){
        if(!is_null($input)) $this->init($input);
        $this->input = str_replace("gh","ق",$this->input);
        $this->input = str_replace("sh","ش",$this->input);
        $this->input = str_replace("kh","خ",$this->input);
        $this->input = str_replace("ch","چ",$this->input);
        // echo $this->input;
        // exit;
        $res = '';
        for($i=0;$i < strlen($this->input); $i++){
            //  echo $this->convert($this->input[$i]);
            $res .= $this->convert($this->input[$i]);
        }
        // echo $res;
        return $res;
        return $this->input;
    }
    private function convert($i){
        //check if already persian value
        if(!ctype_alnum($i)) return $i;
        //check for space
        if($i==' ') return $i;
        $i = strtolower($i);
        if(in_array($i,array_keys($this->map)))
            return $this->map[$i];
        else
            return '';
    }

}
