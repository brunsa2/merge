<?php

class AddDiff extends Diff {
	public function __construct($chunk = '', $newChunkNumber) {
		$this->chunk = (string) $chunk;
		$this->newChunkNumber = $newChunkNumber;
	}
	
	public function getOriginalChunkNumber() {
		return -1;
	}
	
	public function getNewChunkNumber() {
		return $this->newChunkNumber;
	}
	
	public function __toString() {
		return '+ (-, ' . $this->newChunkNumber . ') ' . $this->chunk;
	}
	
	public function getChunk($removeNewline = false) {
		return $removeNewline ? substr($this->chunk, 0, strlen($this->chunk) - 1): $this->chunk;
	}
}

?>