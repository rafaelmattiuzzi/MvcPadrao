<?php

/**
*** Classe padrão para os models contendo a conexão ao banco de dados e funções
*** padrão para execução do CRUD;
**/
class model {

	/**
	*** Declarando variáves necessárias para a classe;
	**/
	protected $pdo;

	/**
	*** Método construtor solicitando o banco de dados;
	**/
	public function __construct() {
		$this->pdo = DB::getConn();
	}
}