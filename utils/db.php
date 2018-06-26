<?php

class _db
{
    private $mysqli;
    private $host;
    private $user;
    private $pass;
    private $db_name;
    private $fetch_res;
    private $selected_field = null;
    private $chunk_return = null;
    private $chunck_size = null;

    public function __construct($host, $user, $pass)
    {
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
        $this->connect();
    }

    public function connect_db($db_name)
    {
        $this->db_name = $db_name;
        return $this->connect();
    }

    public function connect()
    {
        $this->mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db_name);
        return $this;
    }

    public function select($table_name)
    {
        $q = "select * from " . $table_name;
        $res = $this->mysqli->query($q);
        // $rows = array();
        // while($row = $res->fetch_assoc()){
        //     $rows[] = $row;
        // }
        // return $rows;
        // while($row =  $res->fetch_assoc()){
        // yield $row;
        // }
        $this->fetch_res = $res;
        return $this;
    }

	public function query($q,$type='select')
	{
        if($type=='select'){
		  $res = $this->mysqli->query($q);
		  $this->fetch_res = $res;
        }else if($type=='insert'){
            $this->_insert($q);         
        }   
        return $this;
	}

    public function insert_query($params,$table=''){
        
        if(is_array($params)){
            $keys = join(",",array_keys($params));
            $values = join(",",array_values($params));
            $q = "insert into $table($keys)values($values);";
            $params = $q;
        }

        if(is_string($params)){
            if($this->mysqli->query($params) == true){

                }else{
                    die('error inserting '. $params);
                }   
        }
        return $this;
    }

    public function update($params){
        if(is_array($params)){
            $params["id_key"] = isset($params["id_key"]) ?: "id";

            $sql  = "update "   . $params["table"]  . " ";
            $sql .= " set "     . $params["key"]    . "=" . $params["value"] . " ";
            $sql .= " where "   . $params["id_key"] . "=" . $params["id_value"];
            $params = $sql;
        }
        
        if(is_string($params)){
            if($this->mysqli->query($params) == true){

            }else{
                die('error updating ' . $params );
            }
        }
        return $this;
    }

    public function field($field_name)
    {
        $this->selected_field = $field_name;
        return $this;
    }

    public function join($table_name,$table_name2,$field_name,$foreign_table_field_name=null){
        $sql = "select $table_name.* from $table_name join $table_name2 on $table_name.$field_name = $table_name2.";
        if(is_null($foreign_table_field_name)){
            $sql .= "$field_name";
        }else{
            $sql .= "$foreign_table_field_name";
        }
//        var_dump($sql);
//        exit;
        $this->fetch_res = $this->mysqli->query($sql);
        return $this;

    }

    public function for_each($func)
    {
        while ($row = $this->fetch_res->fetch_assoc()) {
            if (!is_null($this->selected_field))
                $func($row[$this->selected_field]);
            else
                $func($row);
        }
        return $this;
    }

    public function toArray()
    {
        $ret = [];
        while ($row = $this->fetch_res->fetch_assoc()) {
            $ret[] = $row;
        }
        if (!is_null($this->selected_field))
            return array_column($ret, $this->selected_field);
        return $ret;
    }

    public function next()
    {
        while ($row = $this->fetch_res->fetch_assoc()) {
            yield $row;
        }
    }

    function __destruct()
    {

    }
}

function db($host, $user, $pass)
{
    return new _db($host, $user, $pass);
}