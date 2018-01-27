<?php

class MyItem extends InferModel {

	protected $table_name = 'my_items';

	/* Example content manipulation functions below.
	 	 To access object properties: $this->vars['column_name_here'] */

	public function getStringContent() {
		$Parsedown = new Parsedown();

		return substr(strip_tags($Parsedown->text($this->vars['content'])), 0, 300) . '...';
	}

	public function getStringDate() {
		return date("F j, Y", strtotime($this->vars['date']));
	}

}
