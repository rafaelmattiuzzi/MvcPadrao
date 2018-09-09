<?php

class config {

	private $environment = 'development',
	//$environment = 'production',
			$config = [];

	public function __construct() {
		if($this->environment === 'development') {
			$this->config = [
				'host' => 'localhost',
				'port' => 3306,
				'dbname' => 'github_mvcPadrao',
				'dbuser' => 'root',
				'dbpass' => '',
				'options' => [
					PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
				],
				'errmode' => PDO::ERRMODE_EXCEPTION,
				'fetch_mode' => PDO::FETCH_ASSOC
			];
		} else if($this->environment === 'production') {
			$this->config = [
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
	}

	public function getConfig() {
		return $this->config;
	}
}