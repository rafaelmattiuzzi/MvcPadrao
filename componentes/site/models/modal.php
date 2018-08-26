<?php
class modal extends model {

	public function get() {
		$dados = array();

		$sql = $this->pdo->query("SELECT * FROM usuarios WHERE id = 3");

		$dados = $sql->fetch();

		return $dados;
	}
}