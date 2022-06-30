<?php

	include_once __DIR__.'/../setup.inc.php';

	class Conexao {
		private $dbHost = 'localhost';
		private $dbUsername = 'root';
		private $dbPwd = '';
		private $dbName = 'carro';

		protected function conectar() {
			$dsn = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName . ';charset=utf8';
			$pdo = new PDO($dsn, $this->dbUsername, $this->dbPwd);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			return $pdo;
		} // conectar

	} // conexao

?>
