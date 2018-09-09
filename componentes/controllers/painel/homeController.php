<?php

class homeController extends controller {

	private $local = 'painel';

	public function index() { 
		$dados = array();
		
		$this->loadTemplate($this->local, 'home', $dados);
	}
	
}