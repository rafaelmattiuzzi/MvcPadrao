<?php

class notfoundController extends controller {

	public function index() { 
		
		$this->loadView('404', array());
	}
	
	public function unauthorized() {
		http_response_code(401);
		$this->loadView('unauthorized', array());
	}
}