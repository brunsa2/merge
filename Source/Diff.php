<?php

class Diff {
	private $chunk = '';
	private $type = '';
	
	public function __construct($chunk = '', $type = '') {
		$this->chunk = (string) $chunk;
		$this->type = $type;
	}
	
	public function __toString() {
		return $this->type . ' ' . $this->chunk;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getChunk() {
		return $this->chunk;
	}
}

?>