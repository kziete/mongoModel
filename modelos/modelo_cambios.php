<?php 
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


$modelo = array(
	'TABLA1' => array(
		'nombre' => 'Tablita 1',
		'campos' => array(
			'NOMBRE__TA1' => array(
				'nombre' => 'Nombre',
				'tipo' => Modelos::text(array())
			),
			'NOMBRE__TA2' => array(
				'nombre' => 'Nombre',
				'tipo' => Modelos::text(array(
					'max_length' => 256
				))
			)
		)
	)
);

foreach ($modelo['TABLA1']['campos'] as $k => $v) {
	echo $v['tipo']->getInput() . "\n";
}
