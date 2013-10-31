<?php 
#holanda!
#chao
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors',1);


include_once('../mustacho.php');
$mustacho = new Mustacho;

class Modelos{
	public static function text($hash){
		return new TextModel($hash);
	}
	public static function prueba($hash=null){
		return new Hija();
	}
}

class ModeloPadre{
	protected $mustacho;
	protected $inputTemplate;
	protected $outputTemplate;

	public function __construct(){
		global $mustacho;
		$this->mustacho = $mustacho;
		$this->inputTemplate = 'inputs/' . get_class($this) . '.html';
		$this->outputTemplate = 'outputs/' . get_class($this) . '.html';
	}

	public function input($hash){
		return $this->mustacho->render( $this->inputTemplate, $hash);
	}
}

class TextModel extends ModeloPadre{
	public $max_length;
	public function __construct($hash){
		parent::__construct($hash);

		$this->max_length = $hash['max_length'] ? $hash['max_length'] : 128;
	}
	public function getInput(){
		$hash = array(
			'dummy' => 'blah'
		);	
		return parent::input($hash);
	}
	public function getOutput(){
		return 0;
	}
}


class AdminPadre{

	public function __construct(){		
		$this->model = new $this->modelName;
	}
	public function getForm(){
		$camposHtml = array();
		foreach ($this->campos as $v) {
			$camposHtml[] = $this->model->{$v}->getInput();
		}
		print_r($camposHtml);
	}
}


class Tabla1{
	public function __construct(){
		$this->campo1 = Modelos::text(array());
		$this->campo2 = Modelos::text(array('max_length' => 256));
	}
}


class Tabla1Admin extends AdminPadre{
	public $model;
	public $modelName = 'Tabla1';
	public $campos = array('campo1','campo2');

	public function __construct(){
		parent::__construct();
	}
}

$a = new Tabla1Admin();
$a->getForm();