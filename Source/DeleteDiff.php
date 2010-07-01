<?php

class DeleteDiff extends Diff {
	public function __construct($chunk = '', $originalChunkNumber) {
		$this->chunk = (string) $chunk;
		$this->originalChunkNumber = $originalChunkNumber;
	}
	
	public function getOriginalChunkNumber() {
		return $this->originalChunkNumber;
	}
	
	public function getNewChunkNumber() {
		return -1;
	}
	
	public function __toString() {
		return '- (' . $this->originalChunkNumber . ', -) ' . $this->chunk;
	}
	
	public function getChunk($removeNewline = false) {
		return $removeNewline ? substr($this->chunk, 0, strlen($this->chunk) - 1): $this->chunk;
	}
}

?>