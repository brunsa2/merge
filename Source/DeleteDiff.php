<?php

class DeleteDiff extends Diff {
	public function __construct($chunk = '', $originalChunkNumber) {
		$this->chunk = (string) $chunk;
		$this->originalChunkNumber = $originalChunkNumber;
	}
	
	public function getNewChunkNumber() {
		throw new Exception('Operation not supported');
	}
	
	public function __toString() {
		return '- (' . $this->originalChunkNumber . ', -) ' . $this->chunk;
	}
}

?>