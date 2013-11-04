<?php 
class Mustacho{
	public $templateDir = '/var/www/mongoModel/widgets/';
	public $mustache;

	public function __construct(){
		require('mustache/src/Mustache/Autoloader.php');
		Mustache_Autoloader::register();
		$this->mustache = new Mustache_Engine;		
	}

	public function render($template, $hash){
		return $this->mustache->render(
			file_get_contents($this->templateDir . $template), 
			$hash
		);
	}
}
