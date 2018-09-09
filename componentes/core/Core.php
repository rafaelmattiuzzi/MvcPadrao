<?php

class Core {

	public function run($local) {
		$url = '/';
		if(isset($_GET['url'])) {
			$url .= $_GET['url'];
		}
		$params = array();
				
		if(!empty($url) && $url != '/') {
			$url = explode('/', $url);
			array_shift($url);

			$currentController = $url[0].'Controller';
			array_shift($url); 

			if(isset($url[0]) && !empty($url[0])) { 
				$currentAction = $url[0];		
				array_shift($url); 
			} else {
				$currentAction = 'index';
			}			

			if(count($url) > 0 && !empty($url[0])) {
				$params = $url;
			}

		} else {
			$currentController = 'homeController';
			$currentAction = 'index';
		}

		$instancia = '';

		if($local === 'site') {
			if(file_exists('../componentes/controllers/'.$local.'/'.$currentController.'.php')) {
				require_once '../componentes/controllers/'.$local.'/'.$currentController.'.php';
				$instancia = new $currentController();

				if(!method_exists($instancia, $currentAction)) {
					$currentController = 'notfoundController';
					$currentAction = 'index';	
				}
			} else {
				$currentController = 'notfoundController';
				$currentAction = 'index';
			}
		}
		else if($local === 'painel') {
			if(file_exists('../../componentes/controllers/'.$local.'/'.$currentController.'.php')) {
				require_once '../../componentes/controllers/'.$local.'/'.$currentController.'.php';
				$instancia = new $currentController();

				if(!method_exists($instancia, $currentAction)) {
					$currentController = 'notfoundController';
					$currentAction = 'index';	
				}
			} else {
				$currentController = 'notfoundController';
				$currentAction = 'index';
			}
		}

		if($instancia === '') {
			$instancia = new $currentController();
		}

		call_user_func_array(array($instancia, $currentAction), $params);
	}
}