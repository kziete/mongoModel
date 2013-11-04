<?php 
#holanda!
#chao
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors',1);


include_once('../mustacho.php');
$mustacho = new Mustacho;

class Modelos{
	public static function id($hash=null){
		return new IdModel($hash);
	}
	public static function text($hash=null){
		return new TextModel($hash);
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

class idModel extends ModeloPadre{
	public function __construct($hash){
		parent::__construct($hash);
	}
	public function getInput(){
		return false;
	}
	public function getOutput($value){
		return $value;
	}
}

class TextModel extends ModeloPadre{
	public $max_length;
	public function __construct($hash){
		parent::__construct($hash);

		$this->max_length = $hash['max_length'] ? $hash['max_length'] : 128;
	}
	public function getInput($campo=null){
		$hash = array(
			'placeholder' => $campo,
			'name' => $campo
		);	
		return parent::input($hash);
	}
	public function getOutput($value){
		return $value;
	}
}




class AdminPadre{

	public function __construct(){		
		global $mustacho;
		$this->mustacho = $mustacho;

		$this->model = new $this->modelName;
	}
	public function getForm(){
		$camposHtml = array();
		foreach ($this->campos as $campo) {
			if($this->model->{$campo}->getInput()){
				$camposHtml[] = array(
					'nombre' => $campo,
					'input' => $this->model->{$campo}->getInput($campo)
				);
			}				
		}
		$output = array(
			'campos' => $camposHtml
		);
		echo $this->mustacho->render('genericos/form.html',$output);
	}

	public function getGrid(){
		$data = $this->model->getRows();
		$ordenado = array();
		foreach ($data as $k => $fila) {
			foreach ($this->campos as $campo) {
				$ordenado[$k][] = $this->model->{$campo}->getOutput($fila[$campo]);
			}
		}

		$output = array(
			'cabecera' => $this->campos,
			'datos' => $ordenado
		);

		echo $this->mustacho->render('genericos/grid.html',$output);
	}
}

class OtroModelo{
	protected $table;
	public function __construct(){
		$this->table = get_class($this);
	}
	public function getRows(){
		//aca se hace el sql
		$sql = "select * from " . $this->table;

		//se retorna una matriz con los datos ()
		return array(
			array(
				'id' => 1,
				'campo1' => 'Juan',
				'campo2' => 'Perez'
			),
			array(
				'id' => 2,
				'campo1' => 'Carlos',
				'campo2' => 'Cruz'
			)
		);
	}
}






class Tabla1 extends OtroModelo{
	public function __construct(){
		$this->id = Modelos::id(array());
		$this->campo1 = Modelos::text(array());
		$this->campo2 = Modelos::text(array('max_length' => 256));
		parent::__construct();
	}
}


class Tabla1Admin extends AdminPadre{
	public $model;
	public $modelName = 'Tabla1';
	public $campos = array('id','campo1','campo2');

	public function __construct(){
		parent::__construct();
	}
}

$a = new Tabla1Admin();
#$a->getGrid();
$a->getForm();
