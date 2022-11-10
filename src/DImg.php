<?php
use Dob\DinamicTag\DTag;
class DImg extends DTag{
	public function __construct($alt, $src) {
		parent::__construct('img');
		$this->alt = $alt;
		$this->src = $src;
	}
}
?>