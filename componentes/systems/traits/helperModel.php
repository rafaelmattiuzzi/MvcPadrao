<?php

trait helperModel
{
	private function createBind($stmt, $fields = null)
	{
		if($fields != null) {
			foreach($fields as $key => $value) {
				$stmt->bindValue(':'.$key, $value);
			}
		}
	}
}