<?php

class AddDiff extends Diff {
	public function __construct($chunk = '', $newChunkNumber) {
		$this->chunk = (string) $chunk;
		$this->newChunkNumber = $newChunkNumber;
	}
	
	public function getOriginalChunkNumber() {
		throw new Exception('Operation not supported');
	}
	
	public function __toString() {
		return '+ (-, ' . $this->newChunkNumber . ') ' . $this->chunk;
	}
}

?>