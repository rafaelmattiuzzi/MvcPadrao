<?php
/*
 *
 *  Incluindo os arquivos necessários para o processo;
 *
 */
require_once 'environment.php';


/*
 *
 *  Preparando variáveis que serão utilizadas;
 *
 */
global $pdo;
$config = array();

/*
 *
 *  Definindo informações para acesso para as etapas de desenvolvimento e produção;
 *
 */

// Desenvolvimento
if(ENVIRONMENT === 'development') {
	define("BASE_SITE", "http://localhost/github/MvcPadrao/public/site/");

	$config = [
		'host' => 'localhost',
		'port' => 3306,
		'dbname' => 'projeto_facebook',
		'dbuser' => 'root',
		'dbpass' => '',
		'options' => [
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		],
		'errmode' => PDO::ERRMODE_EXCEPTION,
		'fetch_mode' => PDO::FETCH_ASSOC
	];

// Produção
} else {
	define("BASE_SITE", "http://meusite.com.br/");

	$config = [
		'host' => '',
		'port' => 3306,
		'dbname' => '',
		'dbuser' => '',
		'dbpass' => '',
		'options' => [
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		],
		'errmode' => PDO::ERRMODE_EXCEPTION,
		'fetch_mode' => PDO::FETCH_ASSOC
	];
}


/*
 *
 *  Realizando a conexão ao banco de dados;
 *
 */

try {
	$pdo = new PDO("mysql:dbname=".$config['dbname'].";charset=utf8mb4;host=".$config['host'], $config['dbuser'], $config['dbpass'], $config['options']);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, $config['errmode']);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $config['fetch_mode']);
} catch(PDOException $e) {
	throw new PDOException('Reveja suas credênciais de conexão ao banco de dados!<br/>Contate o administrador!');
}