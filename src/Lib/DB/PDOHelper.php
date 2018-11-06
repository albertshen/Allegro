<?php

namespace App\Lib\DB;

class PDOHelper
{

	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function insertTable($table, $data) {
		$fields_set = '';
		$params = array();
		foreach($data as $field => $value) {
			if($field == 'created' && !$value) $value = date('Y-m-d H:i:s');
			$fields_set .= "`{$field}` = :{$field}, ";
			$params[':'.$field] = $value;
		}
		$fields_set = substr($fields_set, 0, -2);
		$sql = "INSERT INTO `{$table}` SET {$fields_set}";

		$query = $this->pdo->prepare($sql);   
		$res = $query->execute($params);
		if($res) {
		  return $this->pdo->lastinsertid();
		}
		return false;
	}

	public function updateTable($table, $data, $conditions = array()) {
		$fields_set = '';
		$params = array();
		foreach($data as $field => $value) {
		  $fields_set .= "`{$field}` = :{$field}, ";
		  $params[':'.$field] = $value;
		}
		$fields_set = substr($fields_set, 0, -2);
		if($conditions) {
		  $fields_set .= ' WHERE ';
		  foreach($conditions as $condition) {
		    $symbol = isset($condition[2]) ? $condition[2] : '=';
		    $fields_set .= "`{$condition[0]}` {$symbol} :q_{$condition[0]} AND ";
		    $params[':q_'.$condition[0]] = $condition[1];
		  }
		  $fields_set = substr($fields_set, 0, -5);
		}
		$sql = "UPDATE `{$table}` SET {$fields_set}";
		$query = $this->pdo->prepare($sql);   
		$res = $query->execute($params);
		if($res) {
		  return true;
		}
		return false;
	}
}