<?php

class homeController extends controller {

	private $local = 'site';

	public function index() { 
		$dados = array();
		
		$this->loadTemplate($this->local, 'home', $dados);
	}
	
}