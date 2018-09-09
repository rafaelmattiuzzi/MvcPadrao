<?php

class notfoundController extends controller {

	private $local = 'painel';

	public function index() { 
		
		$this->loadView($this->local, '404', array());
	}
	
	public function unauthorized() {
		http_response_code(401);
		$this->loadView($this->local, 'unauthorized', array());
	}
}