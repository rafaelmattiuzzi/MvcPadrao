<?php

class homeController extends controller {

	public function index() { 
		$dados = array(); 
		
		$this->loadTemplate('home', $dados);
	}

	public function get() {
		$item = new Modal();
		$dados['item'] = $item->get();

		$this->loadTemplate('home', $dados);
	}
	
}