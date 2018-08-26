<?php

class controller {

	public function loadView($viewName, $viewData = array()) { 
		extract($viewData); 
		require '../../componentes/painel/views/'.$viewName.'.php';
	}

	public function loadTemplate($viewName, $viewData = array()) {
		require '../../componentes/painel/views/template.php';
	}

	public function loadViewInTemplate($viewName, $viewData = array()) { 
		extract($viewData);
		require '../../componentes/painel/views/'.$viewName.'.php';
	}
}