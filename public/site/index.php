<?php
session_start();
require_once '../../componentes/config/config.php';

foreach(glob('../../componentes/functions/*.php') as $file) {
	include_once $file;
}

spl_autoload_register(function($class) {
	if(file_exists('../../componentes/site/controllers/'.$class.'.php')) {
		require_once '../../componentes/site/controllers/'.$class.'.php';
	}
	else if(file_exists('../../componentes/site/models/'.$class.'.php')) {
		require_once '../../componentes/site/models/'.$class.'.php';
	}
	else if(file_exists('../../componentes/core/'.$class.'.php')) {
		require_once '../../componentes/core/'.$class.'.php';
	}
});

$local = 'site';

$core = new Core();
$core->run($local);
