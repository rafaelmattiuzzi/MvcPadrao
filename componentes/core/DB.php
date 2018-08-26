<?php
/**
*** Requerendo arquivos necessários;
**/
require_once '../../componentes/config/config.php';

/**
*** Classe de conexão ao banco de dados;
*** Retorna um objeto PDO pelo método estático 'getConn()';
**/

class DB {

	/**
	*** Declarando variáveis;
	**/
	private static $conn = null;


	/**
	*** Método privado de conexão ao banco de dados;
	*** @return null|PDO;
	**/
	private static function connect() {
		try {
			if(self::$conn === null) {
				self::$conn = new PDO('mysql:dbname='.$config['dbname'].';host='.$config['host'], $config['dbuser'], $config['dbpass'], $config['options']);
				self::$conn->setAttribute(PDO::ATTR_ERRMODE, $config['errmode']);
				self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $config['fetch_mode']);
			}
		} catch(PDOException $e) {
			throw new PDOException("Reveja suas credenciais de conexão ao banco de dados!<br/>Contate o adinistrador!");
		}
		return self::$conn;
	}

	/**
	*** Método publico para executar a conexão ao banco de forma externa a classe;
	*** @return PDO;
	**/
	public static function getConn() {
		return static::connect();
	}

	/**
	*** Construtor do tipo privado previne que uma nova instância da Classe seja
	*** criada através do operador 'new' de fora dessa Classe.
	**/
	private function __construct() {
	}

	/**
	*** Método clone do tipo privado previne a clonagem dessa instância da classe;
	**/
	private function __wakeup() {
		static::getConn();
	}
}