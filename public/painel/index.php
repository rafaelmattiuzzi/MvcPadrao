<?php
session_start();

foreach(glob('../../componentes/systems/functions/*.php') as $file) {
	if($file != '../../componentes/systems/functions/index.php') {
		include_once $file;
	}
}

spl_autoload_register(function($class) {
	if(file_exists('../../componentes/controllers/painel/'.$class.'.php')) {
		require_once '../../componentes/controllers/painel/'.$class.'.php';
	}
	else if(file_exists('../../componentes/models/'.$class.'.php')) {
		require_once '../../componentes/models/'.$class.'.php';
	}
	else if(file_exists('../../componentes/core/'.$class.'.php')) {
		require_once '../../componentes/core/'.$class.'.php';
	}
	else if(file_exists('../../componentes/config/'.$class.'.php')) {
		require_once '../../componentes/config/'.$class.'.php';
	}
	else if(file_exists('../../componentes/systems/classes/'.$class.'.php')) {
		require_once '../../componentes/systems/classes/'.$class.'.php';
	}	
	else if(file_exists('../../componentes/systems/traits/'.$class.'.php')) {
		require_once '../../componentes/systems/traits/'.$class.'.php';
	}
});

$local = 'painel';

$core = new Core();
$core->run($local);
