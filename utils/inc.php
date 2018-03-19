<?php
class html
{
	private $templates = array();
	private $base = null;
	private $base_url = null;
	private $presenter = array();
	private $dbstr = "";
	private $current_template = "";
	private $current_tag = "";
	private $with = "";
	private $tag_content="";
	function __construct($base_url=null)
	{
		if(is_null($base_url))
		{
			$this->base = 'http://' . $_SERVER['SERVER_NAME'];
			$this->base_url =  $this->base . $_SERVER['SCRIPT_NAME'];
		}
		$this->add_template("css","<link href='{src}' rel='stylesheet' />")
			 ->add_template("jss","<script src='{src}' type='script/javascript'></script>")
			 ->add_template("a","<a href='{source}'>{description}</a>");
	}
	public function add_template($name,$template)
	{
		$this->templates[$name]=$template;
		return $this;
	}
	public function parse()
	{
		$name = func_get_arg(0);

		$args = array_slice(func_get_args(),1);
		return $this->_echo_template($name,$args);
	}
	public function eparse()
	{
		$name = func_get_arg(0);

		$args = array_slice(func_get_args(),1);
		echo $this->_echo_template($name,$args);
		return $this;
	}
	private function _echo_template($name,$value)
	{
		$template = $this->templates[$name];
		$ret = "";
		foreach(preg_split("/\{[a-zA-Z]*\}/",$template) as $k=>$temp)
		{
		  $ret.= $temp . $value[$k];
		}
		return $ret . PHP_EOL;
	}
	public function add_presenter($name,$func)
	{
		if(is_callable($func))
		{
			$this->presenter[$name] = $func;
		}else
		{
				$this->_dbg("rendere not callable");
		}
	}
	public function present($name,$vm)
	{
			if(isset($this->presenter[$name]))
			{
				return $this->presenter[$name]($vm);
			}else
			{
				$this->_dbg("presenter function not defined");
			}
	}
	public function tpl($name,$template=null)
	{
		if(is_null($template))
		{
			$this->current_template = $name;
			return $this;
		}else
		{
			$this->templates[$name]=$template;
			$this->current_template = $name;
			return $this;
		}
	}
	public function e($in)
	{
		echo  $in;
		return $this;
	}
	
	public function tag($tag)
	{
		$this->current_tag = $tag;
		return $this;
	}
	public function with($in)
	{
		$this->with .= $in . " ";
		return $this;
	}
	public function with_content($in)
	{
		$this->tag_content = $in;
		return $this;
	}
	public function with_type($in)
	{
		$this->tag_type=$in;
		return $this;
	}
	public function et()
	{
		if($this->current_tag=="label")
		{
			echo "<" . $this->current_tag . " " . $this->with . ">" . $this->tag_content . "</" . $this->current_tag . ">";
		}
		
		if($this->current_tag == "input")
		{
			echo "<" . $this->current_tag . " " . $this->with . "/>";
		}
		$this->with = $this->current_tag = $this->tag_content = "";
		return $this;
	}
	public function format()
	{	
		echo $this->_echo_template($this->current_template,func_get_args());
		$this->current_template = "";
		return $this;
	}
	private function _dbg($str){ $this->dbstr .= $str; }
	public function dbg(){ return $this->dbstr; }
	public function base(){ return $this->base; }
	public function base_url(){ return $this->base_url; }
}



$vm = new html();
$vm->add_presenter("links",function($vm)
{
			$html = new html();
			$ret = "";
			foreach($vm as $k => $v)
			{	
				$ret .= $html->parse("a",$v,$k);
				$ret .= "<br/>";
			}
			return $ret;
});




// $vm->
// e("<body>")->
// tpl("rowcol_header","<div class='row'><div class='{class}'>")->
// tpl("rowcol_footer","</div></div>")->
// tpl("divc","<div class='{class}'>")->format('containner')->
// tpl("rowcol_header")->format('col-xs-12')->
// tpl("form_group_header","<div class='form-group'>")->
// tpl("form_group_footer","</div>")->
// tpl("label","<label class='label {lbl}'>{content}</label>")->
// tpl("input","<input type='{type}' value='{value}' />")->
// tpl("rowcol_footer")->format("")->


// tpl("rowcol_header")->format('col-xs-12')->
// 	tpl("form_group_header")->format("")->
// 		tag("label")->with("class='col-xs-12'")->with_content("label")->et()->
// 		tag("input")->with("type='text'")->with("style='background-color:yellow'")->et()->
// 	tpl("form_group_footer")->format("")->
// tpl("rowcol_footer")->format("")->

// e("</body>")->
// e("</html>");
?>