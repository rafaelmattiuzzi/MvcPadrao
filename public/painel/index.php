<?php
session_start();
require_once '../../componentes/config/config.php';

foreach(glob('../../componentes/functions/*.php') as $file) {
	include_once $file;
}

spl_autoload_register(function($class) {
	if(file_exists('../../componentes/painel/controllers/'.$class.'.php')) {
		require_once '../../componentes/painel/controllers/'.$class.'.php';
	}
	else if(file_exists('../../componentes/painel/models/'.$class.'.php')) {
		require_once '../../componentes/painel/models/'.$class.'.php';
	}
	else if(file_exists('../../componentes/core/'.$class.'.php')) {
		require_once '../../componentes/core/'.$class.'.php';
	}
});

$local = 'painel';

$core = new Core();
$core->run($local);
