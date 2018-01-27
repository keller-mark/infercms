<?php

class Users {
	public static function findAll() {
		$query = DB::sql("SELECT * FROM users ORDER BY id DESC");
		$result = array();
		while($row = mysqli_fetch_array($query)) {
			$result[] = self::find($row['id']);
		}
		return $result;
	}

	public static function find($id) {
		return new User(DB::sanitize($id));
	}


	public static function delete($id) {
		$query = DB::sql("DELETE FROM users WHERE id = " . DB::sanitize($id) . "");
	}
}
