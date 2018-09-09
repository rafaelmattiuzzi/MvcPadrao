<?php

/**
*** Classe padrão para os models contendo a conexão ao banco de dados e funções
*** padrão para execução do CRUD;
**/
class model {

	use Pagination;
	use HelperModel;

	/**
	*** Declarando variáves necessárias para a classe;
	**/
	protected $pdo;
	protected $errorInsert;
	protected $errorDelete;
	protected $errorUpdate;
	protected $errorFindAll;
	protected $returnFindAll;
	protected $errorFullQuery;
	protected $returnFullQuery;

	/**
	*** Método construtor solicitando o banco de dados;
	**/
	public function __construct() {
		$this->pdo = DB::getConn();
	}

	/**
	*** Método para insersão de itens na tabela;
	*** @param $data => Deve ser um array onde as chaves são os nomes das colunas da tabela e os valores são os daddos a serem inseridos em cada coluna respectivamente;
	*** @param $field => Array contendo os campos padronizadores e seus valores, ele vai servir para verificar se este item já não foi incluso anteriormete, podem ser passados quantos campos forem necessários;
	*** @param $verify => Este é um campo opcional e ele é utilizado para validação dos dados a serem inseridos, caso ele seja informado deve ser colocado na mesma sequência do array de dados, sendo que o indíce 0 deste se refere ao indíce 0 do array de dados, os dados que não terão validação devem ser inseridos como valor vazio para que não se perca a sequência;
	*** @param $condition_where => recebe a condição do where para verificar se já existe o cadastro, por padrão está 'and';
	**/
	public function insert(array $data, array $field = array(), $verify = array(), $condition = '=', $condition_where = 'AND') {
		if(count($field) > 0) {
			$fields = [];
			foreach($field as $key => $value) {
				$fields[] = $key.' '.$condition.' :'.$key;
			}
			$where = implode(' '.$condition_where.' ', $fields);

			$ver = "SELECT * FROM {$this->table} WHERE ".$where;
			$ver = $this->pdo->prepare($ver);
			foreach($field as $key => $value) {
				$ver->bindValue(':'.$key, $value);
			}
			$ver->execute();

			if($ver->rowCount() == 0 ) {
				$values = implode(', ', array_keys($data));
				$keys = ':'.implode(', :', array_keys($data));

				$sql = "INSERT INTO {$this->table} ({$values}) VALUES ({$keys})";
				$stmt = $this->pdo->prepare($sql);

				$x = 0;
				foreach($data as $key => $value) {
					$param = '';
					if(count($verify) > 0) {
						switch ($verify[$x]) {
							case 'int':
								$param = PDO::PARAM_INT;
								break;					
							case 'bool':
								$param = PDO::PARAM_BOOL;
								break;
							case 'null':
								$param = PDO::PARAM_NULL;
								break;
							case 'string':
								$param = PDO::PARAM_STR;
								break;
							case '':
								$param = '';
								break;
						}
					}
					
					if($param == '') {
						$stmt->bindValue(':'.$key, $value);
					} else {
						$stmt->bindValue(':'.$key, $value, $param);
					}
					
					$x++;
				}

				try {
					$stmt->execute();

					if($stmt->rowCount() == 1) {
						return true;
					}
				} catch (PDOException $e) {
					$this->errorInsert = $e->getMessage();
					return false;
				}
			} else {
				$this->errorInsert = 'Já existe um item inserido com o mesmo dado do campo de verificação';
				return false;
			}
		} else {
			$this->errorInsert = 'É necessário informar os campos padronizadores';
			return false;
		}
	}

	/**
	*** Método para deletar itens da tabela;
	*** @param $field => é um array com os campos utilizados para localizar o item a ser excluido, no qual a chave dos campos é o nome da coluna e os values são os valores das respectivas colunas;
	*** @param $condition => por padrão a condição é de igualdade, mas pode ser inserido outra;
	*** @param $condition_where => por padrão a condição é 'and', mas pode ser inserida outra condição;
	**/
	public function delete(array $field, $condition = '=', $condition_where = 'AND') {
		$fields = [];
		foreach($field as $key => $value) {
			$fields[] = $key.' '.$condition.' :'.$key;
		}
		$where = implode(' '.$condition_where.' ', $fields);

		$ver = "SELECT * FROM {$this->table} WHERE ".$where;
		$ver = $this->pdo->prepare($ver);
		foreach($field as $key => $value) {
			$ver->bindValue(':'.$key, $value);
		}
		$ver->execute();

		if($ver->rowCount() > 0 ) {
			$sql = "DELETE FROM {$this->table} WHERE ".$where;

			$stmt = $this->pdo->prepare($sql);
			foreach($field as $key => $value) {
				$stmt->bindValue(':'.$key, $value);
			}

			try {
				$stmt->execute();

				if($stmt->rowCount() == 1) {
					return true;
				}
			} catch(PDOException $e) {
				$this->errorDelete = $e->getMessage();
				return false;
			}
		} else {
			$this->errorDelete = 'Item não encontrado';
			return false;
		}		
	}

	/**
	*** Método para atualizar itens da tabela;
	*** @param $data => array com os nomes dos campos e os dados referentes a cada campo;
	*** @param $field => é um array com os campos utilizados para localizar o item a ser excluido, no qual a chave dos campos é o nome da coluna e os values são os valores das respectivas colunas;
	*** @param $verify => Este é um campo opcional e ele é utilizado para validação dos dados a serem inseridos, caso ele seja informado deve ser colocado na mesma sequência do array de dados, sendo que o indíce 0 deste se refere ao indíce 0 do array de dados, os dados que não terão validação devem ser inseridos como valor vazio para que não se perca a sequência;
	*** @param $condition => por padrão a condição é de igualdade, mas pode ser inserido outra;
	*** @param $condition_where => recebe a condição do where para verificar se já existe o cadastro, por padrão está 'and';
	**/
	public function update(array $data, array $field, $verify = array(), $condition = '=', $condition_where = 'AND'){
		$fields = [];
		foreach($field as $key => $value) {
			$fields[] = $key.' '.$condition.' :'.$key;
		}
		$where = implode(' '.$condition_where.' ', $fields);

		$ver = "SELECT * FROM {$this->table} WHERE ".$where;
		$ver = $this->pdo->prepare($ver);
		foreach($field as $key => $value) {
			$ver->bindValue(':'.$key, $value);
		}
		$ver->execute();

		if($ver->rowCount() > 0 ) {
			$info = [];

			foreach($data as $key => $value) {
				$info[] = $key.'=:'.$key;
			}

			$info = implode(', ', $info);

			$sql = "UPDATE {$this->table} SET {$info} WHERE ".$where;
			$stmt = $this->pdo->prepare($sql);

			$x = 0;
			foreach($data as $key => $value) {
				$param = '';

				if(count($verify) > 0) {
					switch ($verify[$x]) {
						case 'int':
							$param = PDO::PARAM_INT;
							break;					
						case 'bool':
							$param = PDO::PARAM_BOOL;
							break;
						case 'null':
							$param = PDO::PARAM_NULL;
							break;
						case 'string':
							$param = PDO::PARAM_STR;
							break;
						case '':
							$param = '';
							break;
					}
				}

				if($param == '') {
					$stmt->bindValue(':'.$key, $value);
				} else {
					$stmt->bindValue(':'.$key, $value, $param);
				}

				$x++;
			}

			foreach($field as $key => $value) {
				$stmt->bindValue(':'.$key, $value);
			}

			try {
				$stmt->execute();

				if($stmt->rowCount() == 1) {
					return true;
				}
			} catch(PDOException $e) {
				$this->errorUpdate = $e->getMessage();
				return false;
			}

		} else {
			$this->errorUpdate = 'Item não encontrado para atualização';
			return false;
		}
	}

	/**
	*** Retorna o id do último item inserido na tabela;
	**/
	public function lastId() {
		return $this->pdo->lastInsertId();
	}

	/**
	*** Retorna o último id registrado na tabela;
	**/
	public function maxId(string $field = 'id') {
		$sql = "SELECT MAX({$field}) as max_id FROM ".$this->table;

		$stmt = $this->pdo->prepare($sql);

		try {
			$stmt->execute();

			return $stmt->fetch()['max_id'];
		} catch(PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	*** Método para pegar todas as informações da tabela;
	*** @param $options => é um array que pode ter algumas opções para montagem da query, as chaves das opções devem ser exatamente iguais as opções abaixo para funcionar:
	***		$options = array(
	***			'campos' => array(Aqui informo todos os campos que quero buscas, ou deixo em branco para pegar todos os campos),
	***			'fields' => array(
	***				'nomeColuna' => 'valorValidacao'
	***			),
	***			'condition' => condição de verificação do field, como padrão será '=',
	***			'condition_where' => condição para caso haja mais de field, por padrão é 'and',
	***			'order' => array(
	***				array(
	***					'column' => 'nomeCampo',
	***					'tipo' => 'desc/asc'
	***				)
	***			),
	***			'limit' => 'número de dados a serem consultados',
	***			'return' => 'por padrão será "fetchAll", mas posso informar "fetch"'
	***		);
	**/
	public function findAll($options = array()) {
		$campos = '';
		if(isset($options['campos']) && count($options['campos']) > 0) {
			$campos = implode(', ', $options['campos']);
		} else {
			$campos = '*';
		}
		
		$sql = "SELECT {$campos} FROM ".$this->table;
		
		if(isset($options['fields']) && count($options['fields']) > 0) {
			$condition = '=';
			$condition_where = 'AND';
			$fields = [];
			$where = '';

			if(isset($options['condition'])) {
				$condition = $options['condition'];
			}

			if(isset($options['condition_where'])) {
				$condition_where = $options['condition_where'];
			}

			foreach($options['fields'] as $key => $value) {
				$fields[] = $key.' '.$condition.' :'.$key;
			}

			$where = implode(' '.$condition_where.' ', $fields);

			$sql .= ' WHERE '.$where;
		}

		if(isset($options['order']) && count($options['order']) > 0) {
			$ordem = [];
			foreach($options['order'] as $item) {
				if(isset($item['tipo']) && $item['tipo'] === 'desc') {
					$tipoOrdem = 'DESC';
				} else {
					$tipoOrdem = 'ASC';
				}
				$ordem[] = $item['column'].' '.$tipoOrdem;
			}

			$ordem = implode(', ', $ordem);

			$sql .= ' ORDER BY '.$ordem;
		}

		if(isset($options['limit'])) {
			$limit = intval($options['limit']);

			$sql .= ' LIMIT '.$limit;
		}
		
		$stmt = $this->pdo->prepare($sql);

		if(isset($options['fields']) && count($options['fields']) > 0) {
			foreach($options['fields'] as $key => $value) {
				$stmt->bindValue(':'.$key, $value);
			}
		}
		
		try {
			$stmt->execute();

			if($stmt->rowCount() > 0) {
				if(isset($options['return']) && $options['return'] == 'fetch') {
					$this->returnFindAll = $stmt->fetch();
				} else {
					$this->returnFindAll = $stmt->fetchAll();
				}
				
				return true;
			}
		} catch(PDOException $e) {
			$this->errorFindAll = 'Ocorreu um erro ao tentar executar a consulta!';
			return false;
		}
	}

	/**
	*** Método para executar um tipo de query mais avançada, nele vou receber a query montada e todos os 'binds' necessários;
	*** @param $query => recebo uma string contendo a query já devidamente montada;
	*** @param $binds => recebo todos os binds se necessário;
	*** @param $return => por padrão recebe 'fetchAll', mas posso passar 'fetch';
	**/
	public function fullQuery(string $query, array $binds = null, string $return = 'fetchAll') {
		try {
			//Verificando se a query é um 'insert', 'update' ou 'delete';
			$sql = strtolower($query);
			if((strpos($sql, 'select') !== false) || (strpos($sql, 'update') !== false) || (strpos($sql, 'delete') !== false) || (strpos($sql, 'insert') !== false)) {
				
				//Executando a query
				$stmt = $this->pdo->prepare($query);
				$this->createBind($stmt, $binds);
				$stmt->execute();

				//Retorno
				if($stmt->rowCount() > 0) {
					if($return == 'fetchAll') {
						$this->returnFullQuery = $stmt->fetchAll();
						return true;
					} else {
						$this->returnFullQuery = $stmt->fetch();
						return true;
					}
				} else {
					$this->errorFullQuery = 'Não houve nenhum retorno!';
					return false;
				}

			} else {
				$this->errorFullQuery = 'A string enviada não é uma query!';
				return false;
			}
		} catch(PDOExpetion $e) {
			$this->errorFullQuery = $e->getMessage();
			return false;
		}
	}
}