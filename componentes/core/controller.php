<?php

class controller {

	public function loadView($local, $viewName, $viewData = array()) { 
		extract($viewData);
		$ext = '';
		if($local === 'painel') {
			$ext = '../';
		}
		require $ext.'../componentes/views/'.$local.'/'.$viewName.'.php';
	}

	public function loadTemplate($local, $viewName, $viewData = array()) {
		$ext = '';
		if($local === 'painel') {
			$ext = '../';
		}
		require $ext.'../componentes/views/'.$local.'/template.php';
	}

	public function loadViewInTemplate($local, $viewName, $viewData = array()) { 
		extract($viewData);
		$ext = '';
		if($local === 'painel') {
			$ext = '../';
		}
		require $ext.'../componentes/views/'.$local.'/'.$viewName.'.php';
	}
}