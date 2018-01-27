<?php

class Images {
	public static function findAll() {
		$query = DB::sql("SELECT * FROM images");
		$result = array();
		while($row = mysqli_fetch_array($query)) {
			$result[] = self::find($row['id']);
		}
		return $result;
	}

	public static function findAllApproved() {
		$query = DB::sql("SELECT * FROM images WHERE approved = '1'");
		$result = array();
		while($row = mysqli_fetch_array($query)) {
			$result[] = self::find($row['id']);
		}
		return $result;
	}
	public static function findAllUnapproved() {
		$query = DB::sql("SELECT * FROM images WHERE approved = '0'");
		$result = array();
		while($row = mysqli_fetch_array($query)) {
			$result[] = self::find($row['id']);
		}
		return $result;
	}

	public static function find($id) {
		return new Image($id);
	}
	public static function delete($id) {
		$image = self::find($id);
		$link = $image->getLink();
		$image_link = '../img/uploads/' . $link;
		$image_thumb_link = '../img/uploads/thumb_' . $link;

		if(file_exists($image_link)) {
			unlink($image_link);
		}
		if(file_exists($image_thumb_link)) {
			unlink($image_thumb_link);
		}

		$query = DB::sql("DELETE FROM images WHERE id = " . DB::escape($id) . "");
	}
}
